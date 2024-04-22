.PHONY: help build_php install_php_packages install_npm_packages build up down npm_watch npm_build logs_worker restart_worker logs_api openapi
.DEFAULT_GOAL: help

USER_ID := $(shell id -u)
GROUP_ID := $(shell id -g)
USER:= --user $(USER_ID):$(GROUP_ID)

NODE_VERSION := $(shell cat $(PWD)/.nvmrc | sed 's/^v//')
NODE_CLI:= docker run $(USER) --rm -it \
	--env NPM_CONFIG_UPDATE_NOTIFIER=false \
	--env NODE_OPTIONS=--openssl-legacy-provider \
	--env npm_config_cache=/app/.npm \
	--volume $(PWD):/app \
	--workdir /app \
	node:$(NODE_VERSION)-alpine

COMPOSER_HOME      ?= ${HOME}/.composer
COMPOSER_VERSION   := 2.7.2
COMPOSER_CLI = docker run $(USER) --rm -ti \
	--env COMPOSER_HOME=${COMPOSER_HOME} \
	--volume ${COMPOSER_HOME}:${COMPOSER_HOME} \
	--volume ${PWD}:/app \
	--workdir /app \
	composer:$(COMPOSER_VERSION)

PHP_VERSION := 8.3.6
PHP_CLI = docker run $(USER) --rm -ti \
	--volume ${PWD}:/app \
	--workdir /app \
	php:$(PHP_VERSION)-cli-alpine

help: ## Display this help
	@awk 'BEGIN {FS = ":.* ##"; printf "\n\033[1mUsage:\033[0m\n  make \033[32m<target>\033[0m\n"} /^[a-zA-Z_-]+:.* ## / { printf "  \033[33m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

build_php:
	docker build ./infrastructure/docker/php -t php-long-treatment

install_php_packages:
	$(COMPOSER_CLI) install

install_npm_packages:
	$(NODE_CLI) npm install

npm_build:
	$(NODE_CLI) npm run build

build: ## Build the project
	$(MAKE) build_php
	$(MAKE) install_php_packages
	$(MAKE) install_npm_packages
	$(MAKE) npm_build

up: ## Start the project
	docker-compose up -d

down: ## Stop the project
	docker-compose down -v --remove-orphans

npm_watch: ## watch js / css files changes
	$(NODE_CLI) npm run watch

logs_worker:
	docker-compose logs -f archi_long_treatment_worker_app

restart_worker: ## Restart the worker
	docker-compose restart archi_long_treatment_worker_app

logs_api:
	docker-compose logs -f archi_long_treatment_api_app

logs_server:
	docker-compose logs -f archi_long_treatment_api_server

logs_mercure:
	docker-compose logs -f archi_long_treatment_mercure

openapi: ## Generate openapi.yaml file
	$(PHP_CLI) php bin/console nelmio:apidoc:dump --format=yaml > openapi.yaml

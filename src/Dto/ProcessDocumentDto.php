<?php

namespace App\Dto;

readonly class ProcessDocumentDto
{
    public function __construct(
        public string $id,
    ) {
    }
}

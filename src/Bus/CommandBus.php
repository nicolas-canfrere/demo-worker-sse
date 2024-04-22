<?php

namespace App\Bus;

use App\Bus\Contract\CommandBusInterface;
use App\Bus\Contract\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $commandBus
    ) {
        $this->messageBus = $commandBus;
    }

    public function execute(CommandInterface $command)
    {
        return $this->handle($command);
    }
}

<?php

namespace App\Bus\Command;

use App\Bus\Contract\CommandHandlerInterface;
use App\Message\DocumentMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class ProcessDocumentCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(ProcessDocumentCommand $command): void
    {
        $this->messageBus->dispatch(new DocumentMessage($command->id));
    }
}

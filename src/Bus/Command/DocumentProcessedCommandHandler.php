<?php

namespace App\Bus\Command;

use App\Bus\Contract\CommandHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Email;

class DocumentProcessedCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private HubInterface $hub,
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(DocumentProcessedCommand $command): void
    {
        $id = $command->id;
        $this->hub->publish(new Update(
            '/api/documents/'.$id,
            json_encode(['status' => 'done', 'id' => $id])
        ));
        $email = (new Email())
            ->from('processor@example.com')
            ->to('user@example.com')
            ->subject('Document processed')
            ->text(
                sprintf('The document with id %s has been processed.', $id)
            );
        $this->mailer->send($email);
    }
}

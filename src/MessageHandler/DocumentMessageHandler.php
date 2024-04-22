<?php

namespace App\MessageHandler;

use App\Message\DocumentMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class DocumentMessageHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function __invoke(DocumentMessage $message)
    {
        $this->logger->info('Document message received', ['id' => $message->id]);
        sleep(10);
        $this->logger->info('Document message processed', ['id' => $message->id]);
        try {
            $response = $this->httpClient->request(
                'POST',
                'http://archi_long_treatment_api_server/api/webhook/worker',
                [
                    'json' => ['id' => $message->id]
                ]
            );
        } catch (\Exception $e) {
            $this->logger->error('Error sending message to webhook', ['id' => $message->id, 'error' => $e->getMessage()]);
        }
    }
}

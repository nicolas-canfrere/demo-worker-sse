<?php

namespace App\Controller\Api;

use App\Bus\Command\DocumentProcessedCommand;
use App\Bus\Contract\CommandBusInterface;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WebhookWorkerController
{
    public function __construct(
        private LoggerInterface $logger,
        private CommandBusInterface $commandBus,
    ) {
    }

    #[Route(path: '/api/webhook/worker', methods: ['POST'])]
    #[OA\Tag(name: 'Webhooks')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'id',
                    type: 'string'
                )
            ]
        )
    )]
    #[OA\Response(response: 202, description: 'Document processed')]
    public function __invoke(Request $request): Response
    {
        $data = \json_decode($request->getContent(), true);
        $id = $data['id'];
        $this->logger->info('Worker webhook request received', ['id' => $id]);
        $this->commandBus->execute(new DocumentProcessedCommand($id));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}

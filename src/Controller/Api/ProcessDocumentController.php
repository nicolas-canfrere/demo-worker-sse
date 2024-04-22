<?php

namespace App\Controller\Api;

use App\Bus\Command\ProcessDocumentCommand;
use App\Bus\Contract\CommandBusInterface;
use App\Dto\ProcessDocumentDto;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ProcessDocumentController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    #[Route('/api/documents', name: 'process_document', methods: ['POST'])]
    #[OA\Tag(name: 'Document')]
    #[OA\Response(response: 202, description: 'Document processing started')]
    public function __invoke(
        #[MapRequestPayload]
        ProcessDocumentDto $document,
    ): Response
    {
        $this->commandBus->execute(new ProcessDocumentCommand($document->id));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}

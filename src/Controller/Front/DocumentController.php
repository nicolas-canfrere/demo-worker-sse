<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DocumentController extends AbstractController
{
    #[Route(path: 'front/documents', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('documents/process.html.twig');
    }
}

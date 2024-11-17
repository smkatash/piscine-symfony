<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/error/{message}', name: 'error_page')]
    public function showError(string $message): Response
    {
        return $this->render('error/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/success/{message}', name: 'success_page')]
    public function showSuccess(string $message): Response
    {
        return $this->render('success/index.html.twig', [
            'message' => $message,
        ]);
    }
}

?>
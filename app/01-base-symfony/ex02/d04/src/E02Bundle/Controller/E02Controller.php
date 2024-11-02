<?php

namespace App\E02Bundle\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class E02Controller extends AbstractController
{

    #[Route('/e03', name: 'color-table')]
    public function index(Environment $twig, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $filename = $this->getParameter('filename');
            $output = $message->getOutput();
            file_put_contents($filename, $output, FILE_APPEND);
            return new Response($twig->render('form/index.html.twig', [
                'form' => $form->createView(),
                'last_message' => $output
            ]));
        }
        return new Response($twig->render('form/index.html.twig', [
            'form' => $form->createView(),
            'last_message' => ""
        ]));
    }
}


?>
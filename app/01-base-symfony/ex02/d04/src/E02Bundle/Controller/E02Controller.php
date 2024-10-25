<?php

namespace App\E02Bundle\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class E02Controller extends AbstractController
{

    #[Route('/e02', name: 'form')]
    public function index(Environment $twig, Request $request, LoggerInterface $logger): Response
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
<?php

namespace App\E00Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FirstPageController extends AbstractController
{

    #[Route('/e00/firstpage', name: 'first_page')]
    public function index(): Response
    {
        return new Response('Hello world!');
        
    }
}


?>
<?php

namespace App\E01Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class E01Controller extends AbstractController
{

    #[Route('/e01', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}


?>
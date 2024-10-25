<?php

namespace App\E01Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class E01Controller extends AbstractController
{

    #[Route('/e01', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
    
    #[Route('/e01/{article}', name: 'articles')]
    public function show($article): Response
    {
        $articles = ["elephant", "panda", "zebra"];
        $view = "home/index.html.twig";
        if(in_array($article, $articles)){
            $view = "articles/$article/index.html.twig";
        }
        return $this->render($view);
    }


}


?>
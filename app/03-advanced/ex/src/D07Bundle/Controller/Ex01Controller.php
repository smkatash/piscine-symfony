<?php

namespace App\D07Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Ex01Controller extends AbstractController
{

    #[Route('/ex01', name: 'ex01')]
    public function ex01Action(): Response
    {
        $number = $this->getParameter('d07.number');
        $enable = $this->getParameter('d07.enable');
        return new Response(sprintf('Number: %d, Enable: %s', $number, $enable ? 'true' : 'false'));
    }

}


?>
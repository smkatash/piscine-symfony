<?php

namespace App\D07Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Ex03Controller extends AbstractController
{
    #[Route('/ex03', name: 'ex03')]
    public function extensionAction()
    {
        $string1 = 'hello world from symfony';
        $string2 = 'There are 123 numbers in this 456 text';

        return $this->render('ex03.html.twig', [
            'string1' => $string1,
            'string2' => $string2,
        ]);
    }
}



?>
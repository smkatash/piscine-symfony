<?php

namespace App\E03Bundle\Controller;

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

class E03Controller extends AbstractController
{

    #[Route('/e03', name: 'color-table')]
    public function index(LoggerInterface $logger): Response
    {
        $numberOfShades = $this->getParameter('e03.number_of_colors');
        $colorsAndShades = array(
            "black"=> $this->generateShades("#000000", $numberOfShades),
            "red"=> $this->generateShades("#ff0000", $numberOfShades),
            "blue"=> $this->generateShades("#0000ff", $numberOfShades),
            "green"=> $this->generateShades("#008000", $numberOfShades),
        ); 
        
        return $this->render('color-table/index.html.twig', [
            "colors" => $colorsAndShades,
        ]);
    }

    private function generateShades(string $hexColor, int $shadesCount): array
    {
        $baseRGB = sscanf($hexColor, "#%02x%02x%02x");
        $shades = [];

        for ($i = 0; $i < $shadesCount; $i++) {
            if ($baseRGB[0] == 0 && $baseRGB[1] == 0 && $baseRGB[2] == 0) {
                $increment = $i * 20; 
                $red = min(255, $increment);
                $green = min(255, $increment);
                $blue = min(255, $increment);
            } else {
                $factor = 1 - ($i * 0.2);
                $red = max(0, min(255, $baseRGB[0] * $factor));
                $green = max(0, min(255, $baseRGB[1] * $factor));
                $blue = max(0, min(255, $baseRGB[2] * $factor));
            }
    
            $shade = sprintf("#%02x%02x%02x", $red, $green, $blue);
            $shades[] = $shade;
        }
        
        return $shades;
    }
}


?>
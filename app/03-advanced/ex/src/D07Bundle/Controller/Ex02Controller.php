<?php

namespace App\D07Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class Ex02Controller extends AbstractController
{

    #[Route(path: '/ex02/{count}', name: 'ex02_default_locale',
        requirements: [
            'count' => '\d',
        ],
        defaults: [
            '_locale' => 'en',
            'count' => 0,
        ]
    )]
    #[Route(path: '/{_locale}/ex02/{count}', name: 'ex02',
        requirements: [
            '_locale' => 'en|fr',
            'count' => '\d',
        ],
        defaults: [
            'count' => 0,
        ]
    )]
    public function translationsAction(int $count, TranslatorInterface $translator): Response
    {
        $number = $this->getParameter('d07.number');

        $numberTranslation = $translator->trans('ex02.number', ['%number%' => $number]);
        $countTranslation = $this->getCountTranslation($count, $translator);
        
        return $this->render('ex02.html.twig', [
            'numberTranslation' => $numberTranslation,
            'countTranslation' => $countTranslation,
            'count' => $count
        ]);
    }

    private function getCountTranslation(int $count, TranslatorInterface $translator): string
    {
        if ($count === 0) {
            return $translator->trans('ex02.count.none', ['%count%' => $count]);
        } elseif ($count === 1) {
            return $translator->trans('ex02.count.one', ['%count%' => $count]);
        } else {
            return $translator->trans('ex02.count.number', ['%count%' => $count]);
        }
    }
}

?>
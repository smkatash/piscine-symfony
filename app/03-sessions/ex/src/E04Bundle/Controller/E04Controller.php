<?php

namespace App\E04Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Nubs\RandomNameGenerator\All;
use App\E04Bundle\Service\SessionService;

class E04Controller extends AbstractController
{
    #[Route('/start-session', name: 'start_session')]
    public function anonymousSession(SessionService $sessionService): Response
    {
        $session = $sessionService->getCurrentSession();
        
        if ($session->has('expiration_time')) {
            $expirationTime = $session->get('expiration_time');
            if ($expirationTime && time() > $expirationTime) {
                return $this->redirectToRoute('app_session_expired');
            }
        }
        if ($session->has('anonymous_data')) {
            $session->invalidate();
        }
        $generator = All::create();
        $anonymousName = $generator->getName();
        $session->set('anonymous_data', $anonymousName);
        $session->set('expiration_time', time() + 60);
        $session->start();
        return $this->redirectToRoute('anonymous_page');
    }

    #[Route('/anonymous-page', name: 'anonymous_page')]
    public function anonymousPage(SessionService $sessionService): Response
    {
        $session = $sessionService->getCurrentSession();

        if ($session->has('expiration_time') && time() > $session->get('expiration_time')) {
            return $this->redirectToRoute('app_session_expired');
        }
        $anonymousName = $session->get('anonymous_data');
        $expirationTime = $session->get('expiration_time');
        $remainingTime = $expirationTime - time();

        $sessionStartTime = $session->getMetadataBag()->getCreated();
        $sessionLastUsedTime = $session->getMetadataBag()->getLastUsed();
        $createdDateTime = (new \DateTime())->setTimestamp($sessionStartTime)->format('Y-m-d H:i:s');
        $lastUsedDateTime = (new \DateTime())->setTimestamp($sessionLastUsedTime)->format('Y-m-d H:i:s');
        $lastRequestTimeInSeconds = $sessionLastUsedTime ? (time() - $sessionLastUsedTime) : 0;

        return $this->render('anonymous/page.html.twig', [
            'username' => $anonymousName,
            'last_request_time' => $lastRequestTimeInSeconds,
            'created' => $createdDateTime,
            'last_used' => $lastUsedDateTime,
            'expire_in' => $remainingTime
        ]);
    }

    #[Route('/anonymous-end', name: 'app_session_expired')]
    public function anonymousSessionEnd(SessionService $sessionService): Response
    {
        $session = $sessionService->getCurrentSession();
        if ($session->has('anonymous_data')) {
            $session->invalidate();
            $response = $this->redirectToRoute('home');
            return $response;
        }
        return $this->redirectToRoute('home');
    }
}


?>
<?php

namespace App\E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SecurityController extends AbstractController
{
    #[Route(path: '/e01/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        try {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

    #[Route(path: '/e01/app', name: 'app_post_login')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function main(AuthenticationUtils $authenticationUtils): Response
    {
        try {
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('home/app.html.twig', [
                'last_username' => $lastUsername,
            ]);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

    #[Route(path: '/e01/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

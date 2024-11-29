<?php

namespace App\D09Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['GET'])]
    public function loginAction(): Response
    {
        return $this->render('login/login.html.twig');
    }

    #[Route('/login', name: 'login_check', methods: ['POST'])]
    public function loginCheckAction(Request $request, AuthenticationUtils $authenticationUtils) : Response
    {
        try {
            $data = json_decode($request->getContent(), true);
    
            if (!isset($data['_username'], $data['_password'])) {
                return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_BAD_REQUEST);
            }
        
            $error = $authenticationUtils->getLastAuthenticationError();
            if ($error) {
                return new Response("Error: $error");
            }
            return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route(path: 'logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

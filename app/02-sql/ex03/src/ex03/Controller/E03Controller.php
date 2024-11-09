<?php

namespace App\ex03\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserFormType;
use App\Service\UserService;

class E03Controller extends AbstractController
{

    #[Route('/ex03', name: 'main')]
    public function index(UserService $userService, EntityManagerInterface $entityManager): Response
    {
        $this->createTable($userService, $entityManager);
        return $this->render('home/index.html.twig');
    }

    #[Route('/ex03/user', name: 'new-user')]
    public function newUser(Request $request, UserService $userService, EntityManagerInterface $entityManager): Response 
    {
        $newUser = new User();
        $form = $this->createForm(UserFormType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userService->createUser($newUser, $entityManager);
                return $this->redirectToRoute('all_users');
            } catch (UniqueConstraintViolationException $e) {
                return new Response("Error creating user: username or email already exists.");
            } catch (\Exception $e) {
                return new Response("Error creating user: $e");
            }
        }

        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ex03/list', name: 'all_users')]
    public function listUsers(UserService $userService, EntityManagerInterface $entityManager): Response 
    {
        $users = [];
        try {
            $users = $userService->getUsers($entityManager);
        } catch (\Exception $e) {
                return new Response("Error: $e");
        }
        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }


    public function createTable(UserService $userService, EntityManagerInterface $entityManager): Response
    {
        try {
            $userService->createTable($entityManager);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create the table: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


?>
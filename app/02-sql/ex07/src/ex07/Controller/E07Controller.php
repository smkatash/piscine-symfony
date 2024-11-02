<?php

namespace App\ex07\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserFormType;
use App\Service\UserService;

class E07Controller extends AbstractController
{

    #[Route('/ex07', name: 'main')]
    public function index(UserService $userService, EntityManagerInterface $entityManager): Response
    {
        // TODO solve double creation
        //$this->createTable($userService, $entityManager);
        return $this->render('home/index.html.twig');
    }

    #[Route('/ex07/user', name: 'new-user')]
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

    #[Route('/ex07/list', name: 'all_users')]
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

    #[Route('/ex07/update/{id}', name: 'update_user')]
    public function updateUser(int $id, UserService $userService, Request $request, EntityManagerInterface $entityManager): Response 
    {

        try {
            $user = $userService->getUserById($id, $entityManager);
            if (!$user) {
                return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
            }
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                return new JsonResponse(['message' => 'User updated successfully.'], Response::HTTP_OK);
            }
        } catch (UniqueConstraintViolationException $e) {
            return new Response("Error updating user: username or email already exists.");
        } catch (\Exception $e) {
            return new Response("Error updating user: $e");
        }
        return $this->render('update/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function createTable(UserService $userService, EntityManagerInterface $entityManager)
    {
        try {
            $userService->createTable($entityManager);
        } catch (TableExistsException $e) {
            return ;
        } catch (\PDOException $e) {
            return ;
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create the table: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


?>
<?php

namespace App\ex05\Controller;

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

class E05Controller extends AbstractController
{

    #[Route('/ex05', name: 'main')]
    public function index(UserService $userService, EntityManagerInterface $entityManager): Response
    {
        // TODO solve double creation
        //$this->createTable($userService, $entityManager);
        return $this->render('home/index.html.twig');
    }

    #[Route('/ex05/user', name: 'new-user')]
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

    #[Route('/ex05/list', name: 'all_users')]
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

    #[Route('/ex05/delete/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUserPerRoute(int $id, UserService $userService, EntityManagerInterface $entityManager): Response 
    {

        try {
            $result = $userService->deleteUserById($id, $entityManager);
            if (!$result) {
                return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse(['message' => 'User successfully deleted.'], Response::HTTP_OK);
        } catch (\mysqli_sql_exception $e) {
            return new JsonResponse(['Error' => 'Database error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
                return new Response("Error: $e");
        }
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
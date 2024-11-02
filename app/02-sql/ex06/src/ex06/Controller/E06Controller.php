<?php

namespace App\ex06\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ex06\Service\DatabaseService;
use App\Form\UserFormType;
use App\Entity\User;

class E06Controller extends AbstractController
{

    #[Route('/ex06', name: 'ex06')]
    public function index(DatabaseService $dbService): Response
    {
        $this->createTable($dbService);
        return $this->render('home/index.html.twig');
    }
    
    #[Route('/ex06/user', name: 'new_user')]
    public function newUser(Request $request, DatabaseService $dbService): Response 
    {
        $newUser = new User();
        $form = $this->createForm(UserFormType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $newUser->getUsername();
            $name = $newUser->getName();
            $email = $newUser->getEmail();
            $enable = $newUser->isEnable() ? 1 : 0;
            $birthdate = $newUser->getBirthdate() ? $newUser->getBirthdate()->format('Y-m-d H:i:s') : null;
            $address = $newUser->getAddress();
            $command = "
                INSERT IGNORE INTO ex06 (username, name, email, enable, birthdate, address) 
                VALUES ('$username', '$name', '$email', $enable, '$birthdate', '$address')
            ";
            try {
                $result = $dbService->execute($command);
                if ($result) {
                    return $this->redirectToRoute('all_users');
                }
            } catch (\Exception $e) {
                return new Response("Error user: $e");
            }
        }

        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ex06/list', name: 'all_users')]
    public function listUsers(DatabaseService $dbService): Response 
    {
        $result = [];
        $command = "
            SELECT * FROM ex06;
        ";
        try {
            $result = $dbService->query($command);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
        return $this->render('users/index.html.twig', [
            'users' => $result,
        ]);
    }

    #[Route('/ex06/update/{id}', name: 'update_user')]
    public function updateUser(int $id, Request $request, DatabaseService $dbService): Response 
    {

        try {
            $checkTableExists = "SHOW TABLES LIKE 'ex06'";
            $tableExists = $dbService->execute($checkTableExists);
            if (!$tableExists || empty($tableExists)) {
                return new JsonResponse(['message' => 'Table does not exist.'], Response::HTTP_NOT_FOUND);
            }
            $selectCommand = "SELECT * FROM ex06 WHERE id = $id";
            $userData = $dbService->query($selectCommand);
            if (!$userData) {
                return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
            }

            $user = new User();
            $user->setId($userData[0]['id']);
            $user->setUsername($userData[0]['username']);
            $user->setName($userData[0]['name']);
            $user->setEmail($userData[0]['email']);
            $user->setEnable($userData[0]['enable']);
            $user->setBirthdate(new \DateTime($userData[0]['birthdate']));
            $user->setAddress($userData[0]['address']);
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $username = $user->getUsername();
                $name = $user->getName();
                $email = $user->getEmail();
                $enable = $user->isEnable() ? 1 : 0;
                $birthdate = $user->getBirthdate() ? $user->getBirthdate()->format('Y-m-d H:i:s') : null;
                $address = $user->getAddress();
                $command = "
                    UPDATE ex06 SET username = '$username', name = '$name', email = '$email', enable = $enable,
                    birthdate = '$birthdate', address = '$address' WHERE id = $id";
                $result = $dbService->execute($command);
                if (!$result) {
                    return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
                }
                return new JsonResponse(['message' => 'User successfully updated.'], Response::HTTP_OK);
            }
        } catch (\mysqli_sql_exception $e) {
            return new JsonResponse(['Error' => 'Database error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
                return new Response("Error: $e");
        }
        return $this->render('update/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function createTable(DatabaseService $dbService)
    {
        $command = "
            CREATE TABLE IF NOT EXISTS ex06 (
                id int AUTO_INCREMENT PRIMARY KEY,
                username varchar(50) NOT NULL UNIQUE,
                name varchar(255) NOT NULL,
                email varchar(255) NOT NULL UNIQUE,
                enable BOOLEAN,
                birthdate DATETIME,
                address LONGTEXT
            )";

        try {
            return $dbService->execute($command);
        } catch (\Exception $e) {
            return new Response("Error creating table: $e");
        }
    }
}


?>
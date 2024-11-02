<?php

namespace App\ex04\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ex04\Service\DatabaseService;
use App\Form\UserFormType;
use App\Entity\User;

class E04Controller extends AbstractController
{

    #[Route('/ex04', name: 'ex04')]
    public function index(DatabaseService $dbService): Response
    {
        $this->createTable($dbService);
        return $this->render('base.html.twig');
    }
    
    #[Route('/ex04/user', name: 'new_user')]
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
                INSERT IGNORE INTO ex04 (username, name, email, enable, birthdate, address) 
                VALUES ('$username', '$name', '$email', $enable, '$birthdate', '$address')
            ";
            try {
                $result = $dbService->execute($command);
                if ($result) {
                    return $this->redirectToRoute('all_users');
                }
            } catch (Exception $e) {
                return new Response("Error user: $e");
            }
        }

        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ex04/list', name: 'all_users')]
    public function listUsers(DatabaseService $dbService): Response 
    {
        $result = [];
        $command = "
            SELECT * FROM ex04;
        ";
        try {
            $result = $dbService->query($command);
        } catch (Exception $e) {
            return new Response("Error: $e");
        }
        return $this->render('users/index.html.twig', [
            'users' => $result,
        ]);
    }

    #[Route('/ex04/delete/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUserPerRoute(int $id, DatabaseService $dbService): Response 
    {

        try {
            $checkTableExists = "SHOW TABLES LIKE 'ex04'";
            $tableExists = $dbService->execute($checkTableExists);
            if (!$tableExists || empty($tableExists)) {
                return new JsonResponse(['message' => 'Table does not exist.'], Response::HTTP_NOT_FOUND);
            }
            $selectCommand = "SELECT * FROM ex04 WHERE id = $id";
            $user = $dbService->query($selectCommand);
            if (!$user) {
                return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
            }

            $deleteCommand = "DELETE FROM ex04 WHERE id = $id";
            $result = $dbService->execute($deleteCommand);
            if (!$result) {
                return new JsonResponse(['message' => 'User is not deleted.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse(['message' => 'User successfully deleted.'], Response::HTTP_OK);
        } catch (\mysqli_sql_exception $e) {
            return new JsonResponse(['Error' => 'Database error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
                return new Response("Error: $e");
        }
    }

    public function createTable(DatabaseService $dbService)
    {
        $command = "
            CREATE TABLE IF NOT EXISTS ex04 (
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
        } catch (Exception $e) {
            return new Response("Error creating table: $e");
        }
    }
}


?>
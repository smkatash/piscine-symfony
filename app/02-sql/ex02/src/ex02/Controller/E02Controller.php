<?php

namespace App\ex02\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\ex02\Service\DatabaseService;
use App\Form\UserFormType;
use App\Entity\User;

class E02Controller extends AbstractController
{

    #[Route('/ex02', name: 'ex02')]
    public function index(DatabaseService $dbService): Response
    {
        $this->createTable($dbService);
        return $this->render('base.html.twig');
    }
    
    #[Route('/ex02/user', name: 'new_user')]
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
            $birthdate = $newUser->getBirthdate() ? $newUser->getBirthdate()->format('Y-m-d H:i:s') : null; // Format date
            $address = $newUser->getAddress();
            $command = "
                INSERT IGNORE INTO ex02 (username, name, email, enable, birthdate, address) 
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

    #[Route('/ex02/list', name: 'all_users')]
    public function listUsers( DatabaseService $dbService): Response 
    {
        $command = "
            SELECT * FROM ex02;
        ";
        try {
            $result = $dbService->query($command);
            if ($result) {
                return $this->render('users/index.html.twig', [
                    'users' => $result,
                ]);
            }
        } catch (Exception $e) {
                return new Response("Error creating table: $e");
        }
    }

    public function createTable(DatabaseService $dbService)
    {
        $command = "
            CREATE TABLE IF NOT EXISTS ex02 (
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
<?php

namespace App\ex00\Controller;

use App\ex00\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class E00Controller extends AbstractController
{

    #[Route('/ex00', name: 'ex00')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/ex00/create', name: 'create-table')]
    public function createTable(DatabaseService $dbService): Response
    {
        $command = "
            CREATE TABLE IF NOT EXISTS USERS (
                id int AUTO_INCREMENT PRIMARY KEY,
                username varchar(50) NOT NULL UNIQUE,
                name varchar(255) NOT NULL,
                email varchar(255) NOT NULL UNIQUE,
                enable BOOLEAN,
                birthdate DATETIME,
                address LONGTEXT
            )";

        try {
            $results = $dbService->execute($command);
            return new JsonResponse(['message' => 'Table created successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response("Error creating table: $e");
        }
    }

    #[Route('/ex00/table', name: 'get-table')]
    public function getAllTables(DatabaseService $dbService): Response
    {
        
        $db = $this->getParameter('db_name');
        $command = "
        SELECT *
        FROM information_schema.tables
        WHERE table_schema = '$db'
        ";

        try {
            $results = $dbService->query($command);
            return new JsonResponse(['message' => $results], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response("Error creating table: $e");
        }
    }
}


?>
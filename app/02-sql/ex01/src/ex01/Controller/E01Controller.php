<?php

namespace App\ex01\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class E01Controller extends AbstractController
{

    #[Route('/ex01', name: 'main')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/ex01/create', name: 'create-table')]
    public function createTable(EntityManagerInterface $entityManager): Response
    {
        try {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
            $classes = [$entityManager->getClassMetadata(User::class)];
            $schemaTool->createSchema($classes);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to create the table: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $user_table = $entityManager->getRepository(User::class);
        if (!$user_table) {
            return new JsonResponse(['message' => 'Table is not created.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(['message' => 'Table created successfully.'], Response::HTTP_OK);
    }

    #[Route('/ex01/drop', name: 'drop-table')]
    public function dropTable(EntityManagerInterface $entityManager)
    {
        try {
            $connection = $entityManager->getConnection();
            $connection->executeStatement('DROP TABLE IF EXISTS user');

            return new JsonResponse(['message' => 'Table dropped successfully.'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to drop the table: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


?>
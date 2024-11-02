<?php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
class UserService
{

    public function createUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function getUsers(EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(User::class);
        return $userRepository->findAll();
    }

    public function getUserById(int $id, EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(User::class);
        return $userRepository->find($id);
    }
    
    public function deleteUserById(int $id, EntityManagerInterface $entityManager)
    {
        $user = $this->getUserById($id, $entityManager);
        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            return true;
        }
        return false;
    }

    public function createTable(EntityManagerInterface $entityManager)
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $classes = [$entityManager->getClassMetadata(User::class)];
        $schemaTool->createSchema($classes);
    }

}
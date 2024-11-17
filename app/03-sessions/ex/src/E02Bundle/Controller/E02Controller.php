<?php

namespace App\E02Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

#[IsGranted('ROLE_ADMIN')]
class E02Controller extends AbstractController
{

    #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
    #[Route('/e02/admin', name: 'admin_dashboard')]
    public function adminDashboard(EntityManagerInterface $entityManager): Response
    {
        $users = [];
        try {
            $userRepository = $entityManager->getRepository(User::class);
            $users = $userRepository->findAll();
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
        
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
    #[Route('/e02/admin/delete-user/{id}', name: 'admin_delete_user',  methods: ['POST'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['id' => $id]);
            if (!$user) {
                return new JsonResponse(['message' => 'User does not exist.'], Response::HTTP_NOT_FOUND);
            }

            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('error_page', [
                    'message' => 'Admin users cannot be deleted.'
                ]);
            }
            
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}


?>
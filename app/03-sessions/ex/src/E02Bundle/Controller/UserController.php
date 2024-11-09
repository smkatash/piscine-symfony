<?php 
namespace App\E02Bundle\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/e02/make-admin/{email}', name: 'make_admin')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function makeAdmin(string $email, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $user->setRoles(['ROLE_ADMIN']);
        $entityManager->flush();

        return new Response('User has been assigned as an admin.');
    }
}

?>
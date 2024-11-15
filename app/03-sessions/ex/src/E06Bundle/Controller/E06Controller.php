<?php

namespace App\E06Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface; 


class E06Controller extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/e06/edit-post/{id}', name: 'app_post_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function editPost(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $postRepository = $entityManager->getRepository(Post::class);
        $post = $postRepository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Post not found.');
        }

        $user = $this->getUser();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new \DateTimeImmutable());
            $user->addPostEdit($post);

            $entityManager->persist($user);
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts');
        }
        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

}


?>
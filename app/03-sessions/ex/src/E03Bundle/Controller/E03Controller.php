<?php

namespace App\E03Bundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Post;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\PostFormType;
use Psr\Log\LoggerInterface; 

class E03Controller extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/e03/posts', name: 'app_posts')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function listAllPosts(EntityManagerInterface $entityManager): Response
    {
        $posts = [];
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
        
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/e03/create-post', name: 'app_create_post')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createPost(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $post->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts');
        }

        return $this->render('posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/e02/posts/{id}', name: 'app_get_post')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getPost(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->findOneBy(['id' => $id]);
            if (!$post) {
                return new JsonResponse(['message' => 'Post does not exist.'], Response::HTTP_NOT_FOUND);
            }
            $this->logger->info('Number of likes: ' . count($post->getLikedByUsers()));
            $this->logger->info('Number of dislikes: ' . count($post->getDislikedByUsers()));
            return $this->render('posts/post.html.twig', [
                'post' => $post,
            ]);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }

    }
}


?>
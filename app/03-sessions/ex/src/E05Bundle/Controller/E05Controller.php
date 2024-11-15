<?php

namespace App\E05Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Post;
use Psr\Log\LoggerInterface; 

class E05Controller extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/e05/post-reaction/{id}', name: 'app_post_reaction')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function listPostReactions(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->findOneBy(['id' => $id]);
            if (!$post) {
                return new JsonResponse(['message' => 'Post does not exist.'], Response::HTTP_NOT_FOUND);
            }

            $likes = count($post->getLikedByUsers());
            $dislikes = count($post->getDislikedByUsers());
            return new JsonResponse([
                'likes' => $likes,
                'dislikes' => $dislikes
            ]);
        } catch (\Exception $e) {
            return new Response("Error: $e");
        }
    }

    #[Route('/e05/post/{id}/like', name: 'app_post_like', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function likePost(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->findOneBy(['id' => $id]);

            if (!$post) {
                return new JsonResponse(['message' => 'Post does not exist.'], Response::HTTP_NOT_FOUND);
            }

            $user = $this->getUser();
            $likedByUsers = $post->getLikedByUsers();
            if ($likedByUsers->contains($user)) {
                $this->addFlash('success', 'The post is already liked by you!');
                return $this->redirectToRoute('app_get_post', ['id' => $id]);
            }

            $dislikedByUsers = $post->getDislikedByUsers();
            if ($dislikedByUsers->contains($user)) {
                $post->removeDislikeByUser($user);
                $user->getDislikedPosts()->removeElement($post);
            }
            $user->getLikedPosts()->add($post);
            $entityManager->persist($user); 
            
            $post->addLikedByUser($user);
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'You liked the post successfully!');
            return $this->redirectToRoute('app_get_post', ['id' => $id]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred: ' . $e->getMessage());
            return $this->redirectToRoute('app_get_post', ['id' => $id]);
        }
    }

    #[Route('/e05/post/{id}/dislike', name: 'app_post_dislike', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function dislikePost(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->findOneBy(['id' => $id]);

            if (!$post) {
                return new JsonResponse(['message' => 'Post does not exist.'], Response::HTTP_NOT_FOUND);
            }

            $user = $this->getUser();
            $dislikedByUsers = $post->getDislikedByUsers();
            if ($dislikedByUsers->contains($user)) {
                $this->addFlash('success', 'The post is already disliked by you!');
                return $this->redirectToRoute('app_get_post', ['id' => $id]);
            }
            $likedByUsers = $post->getLikedByUsers();
            if ($likedByUsers->contains($user)) {
                $post->removeLikedByUser($user);
                $user->getLikedPosts()->removeElement($post);
            }
            $user->getDislikedPosts()->add($post);
            $entityManager->persist($user); 
            $post->addDislikeByUser($user);
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'You disliked the post successfully!');
            return $this->redirectToRoute('app_get_post', ['id' => $id]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred: ' . $e->getMessage());
            return $this->redirectToRoute('app_get_post', ['id' => $id]);
        }
    }

}


?>
<?php

namespace App\D09Bundle\Controller;

use App\D09Bundle\Form\PostFormType;
use App\D09Bundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class PostController extends AbstractController
{
    #[Route('/', name: 'post_index')]
    public function defaultAction(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        $form = null;
        $posts = [];

        try {
            if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser() instanceof UserInterface) {
                $post = new Post();
                $form = $this->createForm(PostFormType::class, $post)->createView();
            }
            $postRepository = $entityManager->getRepository(Post::class);
            $posts = $postRepository->findBy([], ['created' => 'DESC']);
            
            return $this->render('post/post.html.twig', [
                'form' => $form, 
                'is_logged_in' => $form !== null,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    #[Route('/post/create', name: 'post_create', methods: ['POST'])]
    public function createPostAction(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $post = new Post();
            $form = $this->createForm(PostFormType::class, $post);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($post);
                $entityManager->flush();
                
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Post created successfully!',
                    'newPost' => [
                        'id' => $post->getId(),
                        'title' => $post->getTitle(),
                        'created' => $post->getCreated()->format('Y-m-d H:i:s'),
                    ],
                ]);
                
            }
    
            return new JsonResponse(['error' => 'Invalid form submission.'], Response::HTTP_BAD_REQUEST);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                ['error' => 'A post with the same title already exists. Please choose a different title.'],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/posts', name: 'posts')]
    public function listAllPosts(EntityManagerInterface $entityManager): Response
    {
        $posts = [];
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $posts = $postRepository->findBy([], ['created' => 'DESC']);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }


    #[Route('/view/{id}', name: 'post_view', methods: ['GET'])]
    public function viewAction(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->find($id);

            if (!$post) {
                return new JsonResponse(['error' => 'Post not found.'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created' => $post->getCreated()->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/delete/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function deleteAction(int $id, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): JsonResponse
    {
        try {
            if (!$tokenStorage->getToken() || !$tokenStorage->getToken()->getUser() instanceof UserInterface) {
                return new JsonResponse(['error' => 'You must be logged in to delete a post.'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $postRepository = $entityManager->getRepository(Post::class);
            $post = $postRepository->find($id);

            if (!$post) {
                return new JsonResponse(['error' => 'Post not found.'], JsonResponse::HTTP_NOT_FOUND);
            }
            $entityManager->remove($post);
            $entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Post deleted successfully!', 'postId' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while deleting the post: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}


?>
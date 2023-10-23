<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostFormType;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/create', name: 'app_create_post')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(PostFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = new Post();

            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_posts');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/post/{id}', name: 'app_show_post')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'app_delete_post')]
    public function delete(Post $post): Response
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_posts');
    }

    #[Route('/post/{id}/edit', name: 'app_edit_post')]
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_posts');
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}


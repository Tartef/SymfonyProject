<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostFormType;
use Knp\Component\Pager\PaginatorInterface;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $postRepository = $this->entityManager->getRepository(Post::class);
        $query = $postRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Récupère le numéro de la page de la requête
            3 // Nombre d'éléments par page
        );

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
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


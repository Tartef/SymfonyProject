<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostFormType;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(PostFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traite la soumission du formulaire, enregistre le post en base de données, etc.

            return $this->redirectToRoute('home'); // Redirige vers la page d'accueil après la création du post.
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

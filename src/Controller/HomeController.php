<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PostRepository $postRepository): Response
    {
        // Récupérer tous les posts en utilisant la méthode personnalisée
        $posts = $postRepository->findAll();

        return $this->render('main/home.html.twig', [
            'posts' => $posts,
        ]);
    }
}

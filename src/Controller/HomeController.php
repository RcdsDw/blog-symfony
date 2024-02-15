<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PostRepository $postRepository): Response
    {   
        $posts = $postRepository->findAll();

        $currentUser = $this->getUser();    

        $sortedPosts = $posts;
        usort($sortedPosts, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });


        return $this->render('main/home.html.twig', [
            'posts' => $sortedPosts,
            'author_name' => $currentUser->getUsername(),
            'author_img' => $currentUser->getImageName(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostDetailsController extends AbstractController
{
    #[Route('/details', name: 'app_post_details')]
    public function index(PostRepository $postRepository): Response
    {
        $request = Request::createFromGlobals();
        $postId = $request->query->get('post_id');
        $post = $postRepository->find($postId);

        return $this->render('post_details/index.html.twig', [
            'post' => $post,
        ]);
    }
}

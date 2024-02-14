<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostDetailsController extends AbstractController
{
    #[Route('/details/{id}', name: 'app_post_details')]
    public function index(Post $post): Response
    {
        $sortedComments = $post->getComments()->toArray();
        usort($sortedComments, function ($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return $this->render('post_details/index.html.twig', [
            'post' => $post,
            'comments' => $sortedComments,
        ]);
    }

    #[Route('/comment/{id}', name: 'app_post_new_comment', methods: ['POST'])]
    public function new(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $currentUser = $this->getUser();
        $text = $request->request->get('comment');

        if ($currentUser && $text) {
            $comment->setUser($currentUser);
            $comment->setPost($post);
            $comment->setText($text);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','');
        }

        return $this->redirectToRoute('app_post_details', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }
}

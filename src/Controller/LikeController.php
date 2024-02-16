<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/likes/{id}', name: 'app_likes')]
    public function LikesIncrement(Post $post, EntityManagerInterface $entityManager)
    {      
        $likes = $post->getLikes();
        
        if (count($likes) > 0) {
            foreach ($likes as $like) {
                if($this->getUser()->getId() == $like->getUser()->getId() && $post->getId() == $like->getPost()->getId()) {
                    $entityManager->remove($like);
                    $entityManager->flush();
                    if (count($likes) > 0) {
                        $countLikes = count($likes) - 1;
                    } else {
                        $countLikes = 0;
                    }
                    break;
                }
                else {
                    $newLike = new Like();
                    $newLike->setUser($this->getUser());
                    $newLike->setPost($post);
                    $newLike->setTimestamp(time());
                    $entityManager->persist($newLike);
                    $entityManager->flush();
                    $countLikes = count($likes) + 1;
                }
            }
        } else {
            $newLike = new Like();
            $newLike->setUser($this->getUser());
            $newLike->setPost($post);
            $newLike->setTimestamp(time());
            $entityManager->persist($newLike);
            $entityManager->flush();
            $countLikes = count($likes) + 1;
        }

        return new JsonResponse($countLikes);
    }
}
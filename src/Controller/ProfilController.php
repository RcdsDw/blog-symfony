<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger
    ): Response
    {
        $currentUser = $userRepository->find($this->getUser());
        $form = $this->createForm(UserType::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('user_image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e);
                }

                $currentUser->setImageName($newFilename);
            }

            $currentUser->setUsername($form->get('username')->getData());
            $entityManager->persist($currentUser);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_profil', ['id'=> $currentUser->getId()]);
        }

        return $this->render('profil/index.html.twig', [
            'user' => $currentUser,
            'profilForm' => $form->createView(),
        ]);
    }
}

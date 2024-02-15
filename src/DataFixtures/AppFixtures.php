<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepo;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepo) {
        $this->passwordHasher = $passwordHasher;
        $this->userRepo = $userRepo;
    }
    public function load(ObjectManager $manager): void
    {
        $users = [];

        $admin = new User();
            $admin->setUsername("admin");
            $admin->setEmail("admin@admin.com");
            $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin"));
            $admin->setImageName(null);
            $admin->setCreatedAt(new \DateTimeImmutable());
            $admin->setRoles(["ROLE_ADMIN"]);

            $manager->persist($admin);

        for ($i = 0; $i < 60; $i++) {
            $faker = Factory::create();

            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password()));
            $user->setImageName($faker->imageUrl());
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(["ROLE_USER"]);

            array_push($users, $user);
            $manager->persist($user);
        }

        for ($i = 0; $i < 60; $i++) {
            $faker = Factory::create();

            $post = new Post();
            $post->setTitle($faker->sentence(3));
            $post->setText($faker->paragraph());
            $post->setImageName("https://picsum.photos/1900/2000?grayscale&image=$i");
            $post->setCategorie($faker->word());
            $post->setLikes(rand(1,1000));
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUser($admin);

            $numComments = rand(2, 7);
            for ($j = 0; $j < $numComments; $j++) {
                $comment = new Comment();
                $comment->setText($faker->paragraph());
                $comment->setCreatedAt(new \DateTimeImmutable());
                $comment->setLikes(rand(1, 100));
                $comment->setPost($post);
                $comment->setUser($users[rand(0, count($users) -1)]);

                $manager->persist($comment);
                }

            $manager->persist($post);
        }
        
        $manager->flush();
    }
}
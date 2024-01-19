<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [];

        for ($i = 0; $i < 60; $i++) {
            $faker = Factory::create();

            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setImg($faker->imageUrl());
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
            $post->setImg("https://picsum.photos/1900/2000?grayscale&image=$i");
            $post->setCategorie($faker->word());
            $post->setLikes(rand(1,1000));
            $post->setCreatedAt(new \DateTimeImmutable());

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
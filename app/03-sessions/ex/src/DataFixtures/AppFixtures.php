<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Vote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->hasher->hashPassword($user, 'password123'));
            $user->setName($faker->name);
            $manager->persist($user);
            $users[] = $user;
        }

        $posts = [];
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence);
            $post->setContent($faker->paragraph);
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable());
            $post->setAuthor($faker->randomElement($users));
            $manager->persist($post);
            $posts[] = $post;
        }

        foreach ($posts as $post) {
            $likedByUsers = $faker->randomElements($users, $faker->numberBetween(1, 5));
            foreach ($likedByUsers as $user) {
                $post->addLikedByUser($user);
                $user->addLikedPost($post);
            }

            $remainingUsers = array_diff($users, $likedByUsers);
            $dislikedByUsers = $faker->randomElements($remainingUsers, $faker->numberBetween(0, 3));
            foreach ($dislikedByUsers as $user) {
                $post->addDislikeByUser($user);
                $user->addDislikedPost($post);
            }
        }

        $manager->flush();
    }
}

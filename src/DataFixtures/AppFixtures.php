<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Unicorn;
use App\Enum\Status;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $user = new User();
            $post = new Post();
            $unicorn = new Unicorn();

            if ($i % 3 == 0) {
                $user->setName("I'm the user with the ID of: " . $i);
            } else {
                $user->setName("My ID is perfectly diveded by 3");
            }
            $user->setEmail("user" . $i . "@gmail.com");
            $manager->persist($user);

            $post->setUser($user);
            $post->setUnicorn($unicorn);
            if ($i % 2 == 0) {
                $post->setContent("Even post no. " . $i);
            } else {
                $post->setContent("Odd post no. " . $i);
            }
            $manager->persist($post);

            if ($i % 2 == 0) {
                $unicorn->setName("Even unicorn no." . $i);
            } else {
                $unicorn->setName("Odd unicorn no. ". $i);
            }

            if ($i % 3 == 0) {
                $unicorn->setContent("This unicorn is perfectly divided by 3");
                $unicorn->setStatus(Status::PURCHASED);
            } else {
                $unicorn->setContent("I'm a red unicorn");
                $unicorn->setStatus(Status::UNPURCHASED);
            }

            if ($i % 5 == 0) {
                $unicorn->setPrice(5000);
            } else {
                $unicorn->setPrice(2000);
            }
            $manager->persist($unicorn);
        }
        $manager->flush();
    }
}

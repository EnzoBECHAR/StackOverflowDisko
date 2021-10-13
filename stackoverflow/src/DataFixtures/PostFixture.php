<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;


class PostFixture extends Fixture
{

    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 13; $i++){
            $post = new Post();
            $post->setTitle("titre du post $i");
            $post->setContent("contenue de l'article n° $i");
            $post->setCreatedAt(new \DateTimeImmutable());
            $user = $this->em
                ->getRepository(User::class)
                ->find(1);
            $post->setUserId($user);

            $manager->persist($post);
        }

        $manager->flush();

        $manager->flush();
    }
}

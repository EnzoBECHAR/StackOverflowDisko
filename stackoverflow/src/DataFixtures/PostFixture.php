<?php

namespace App\DataFixtures;

use App\Entity\Comment;
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
        for ($j = 0; $j < 5; $j++){
            $user = new User();
            $user->setEmail("test$j@gmail.com");
            $user->setUsername("test$j");
            $user->setPassword("password");
            $manager->persist($user);

            for ($i = 1; $i <= 13; $i++){
                $post = new Post();
                $post->setTitle("titre du post $i");
                $post->setContent("contenue de l'article n° $i");
                $post->setCreatedAt(new \DateTimeImmutable());
                $post->setUserId($user);

                $manager->persist($post);

                for ($l=0; $l < 3; $l++){
                    $comment = new Comment();
                    $comment->setCorps("corps$l");
                    $comment->setCreatedAt(new \DateTimeImmutable());
                    $comment->setUserId($user);
                    $comment->setPostId($post);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}

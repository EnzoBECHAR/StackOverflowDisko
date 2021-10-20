<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreationPostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->findAll();
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function post(Post $post){
        return $this->render('post/post.html.twig', [
            'post' => $post
        ]);
    }

    public function creationPost(Request $request){
        $post = new Post();
        $form = $this->createForm(CreationPostType::class, $post);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setUserId($user);
            $post->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('post/creation_post.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

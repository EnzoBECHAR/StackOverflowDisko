<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommenType;
use App\Form\CreationPostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->findAll();
        $posts = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function post(Post $post, Request $request){
        $comment = new Comment();
        $form = $this->createForm(CommenType::class, $comment);

        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();


        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTimeImmutable())
                    ->setPostId($post)
                    ->setUserId($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('post', ['id' => $post->getId()]);
        }
        return $this->render('post/post.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView()
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

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'posts.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('post/index.html.twig');
        // return new Response(
        //     'list all posts ' . $this->generateUrl('posts.index')
        // );
    }

    #[Route('/posts/user/{id}', name: 'posts.user', methods: ['GET'])]
    public function user(int $id): Response
    {

        return $this->render('post/index.html.twig');

        // return new Response(
        //     'list user all posts ' . $this->generateUrl('posts.user')
        // );

    }


    #[Route('/toggleFollow/{user}', name: 'toggleFollow', methods: ['GET'])]
    public function toggleFollow(int $user): Response
    {
        return new Response(
            'toggle follow ' . $this->generateUrl('toggleFollow')
        );
    }

    #[Route('/post/new', name: 'posts.new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('post/new.html.twig');

    }

    #[Route('/post/{id}', name: 'posts.show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('post/show.html.twig');

    }

    #[Route('/post/{id}/edit', name: 'posts.edit', methods: ['GET', 'POST'])]
    public function edit(int $id): Response
    {
        // return $this->redirectToRoute('posts.index');

        return $this->render('post/edit.html.twig');

    }

    #[Route('/post/{id}/delete', name: 'posts.delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        return new Response(
            'deleted successfully'
        );

    }

}
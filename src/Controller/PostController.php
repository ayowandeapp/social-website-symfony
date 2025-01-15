<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    #[Route('/', name: 'posts.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', );
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

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return new Response(
            'toggle follow ' . $this->generateUrl('toggleFollow')
        );
    }

    #[Route('/post/new', name: 'posts.new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $post = new Post;
        // $post->setTitle('Write a post');
        // $post->setContent('');

        $form = $this->createForm(PostType::class, $post);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $form->getData();
            $post->setCreatedAt(new \DateTimeImmutable);
            $post->setUpdatedAt(new \DateTimeImmutable);
            dd($post);

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('post.index');
        }
        // return new Response('Saved new Post wit Id' . $post->getId());
        return $this->render('post/new.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/post/{id}/', name: 'posts.show', methods: ['GET'])]
    public function show(int $id): Response
    {
        return $this->render('post/show.html.twig');

    }

    #[Route('/post/{id}/edit', name: 'posts.edit', methods: ['GET', 'POST'])]
    public function edit(int $id): Response
    {
        // return $this->redirectToRoute('posts.index');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('post/edit.html.twig');

    }

    #[Route('/post/{id}/delete', name: 'posts.delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return new Response(
            'deleted successfully'
        );

    }

}

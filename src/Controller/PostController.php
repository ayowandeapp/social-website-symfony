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
use User;

class PostController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    #[Route('/', name: 'posts.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $posts = $this->em->getRepository(Post::class)
            ->findAllPosts(
                $request->query->getInt('page', 1), /* page number */
                10 /* limit per page */
            );
        return $this->render('post/index.html.twig', ['posts' => $posts]);
        // return new Response(
        //     'list all posts ' . $this->generateUrl('posts.index')
        // );
    }

    #[Route('/posts/user/{user}', name: 'posts.user', methods: ['GET'])]
    public function user(Request $request, int $user): Response
    {
        $posts = $this->em->getRepository(Post::class)
            ->findAllUserPosts(
                $request->query->getInt('page', 1), /* page number */
                10, /* limit per page */
                $user
            );
        return $this->render('post/index.html.twig', ['posts' => $posts]);


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
        $form = $this->createForm(PostType::class, $post);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $post = $form->getData();
            $post->setUser($this->getUser());
            $post->setCreatedAt(new \DateTimeImmutable);
            $post->setUpdatedAt(new \DateTimeImmutable);
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('posts.index');
        }
        // return new Response('Saved new Post wit Id' . $post->getId());
        return $this->render('post/new.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/post/{post}/', name: 'posts.show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        // dd($post->getUser()->getName());
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);

    }

    #[Route('/post/{post}/edit', name: 'posts.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $editPost = $form->getData();
            $editPost->setUpdatedAt(new \DateTimeImmutable);

            $this->em->persist($editPost);
            $this->em->flush();
            return $this->redirectToRoute('posts.index');
        }

        return $this->render('post/edit.html.twig', ['form' => $form]);

    }

    #[Route('/post/{post}/delete', name: 'posts.delete', methods: ['POST'])]
    public function delete(Post $post): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->em->remove($post);
        $this->em->flush();

        return $this->redirectToRoute('posts.index');

    }

}

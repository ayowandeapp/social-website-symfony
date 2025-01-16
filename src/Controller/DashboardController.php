<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ChangePasswordFormType;
use App\Form\ImageFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }


    #[Route('/dashboard/profile', name: 'app_profile')]
    public function profile(Request $request): Response
    {
        $image = new Image;
        $imageForm = $this->createForm(ImageFormType::class, $image);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $image = $imageForm->getData();
            dd($image);

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('app_profile');
        }

        $user = $this->getUser();
        $userForm = $this->createForm(UserFormType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $image = $userForm->getData();
            dd($image);

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('app_profile');
        }

        $passwordForm = $this->createForm(ChangePasswordFormType::class, $user);

        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $image = $passwordForm->getData();
            dd($image);

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('app_profile');
        }
        return $this->render('dashboard/edit.html.twig', [
            'imageForm' => $imageForm,
            'userForm' => $userForm,
            'passwordForm' => $passwordForm
        ]);
    }
}

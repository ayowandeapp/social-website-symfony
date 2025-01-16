<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ChangePasswordFormType;
use App\Form\DeleteAccountType;
use App\Form\ImageFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function profile(Request $request, Security $security): Response
    {
        $user = $this->getUser();

        $image = new Image;
        $imageForm = $this->createForm(ImageFormType::class, $image);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $image = $imageForm->getData();
            $image->setPath($imageForm->get('imageFile')->getData()->getClientOriginalName());

            if ($user->getImage()) {
                $this->em->remove($user->getImage());
            }

            $user->setImage($image);
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash(
                'image-notice',
                'Image saved!'
            );

            return $this->redirectToRoute('app_profile');
        }

        $userForm = $this->createForm(UserFormType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $userForm->getData();

            $this->em->persist($user);
            $this->em->flush();


            $this->addFlash(
                'user-notice',
                'User info saved!'
            );

            return $this->redirectToRoute('app_profile');
        }

        $passwordForm = $this->createForm(ChangePasswordFormType::class, $user);

        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash(
                'password-notice',
                'Password info updated!'
            );

            return $this->redirectToRoute('app_profile');
        }

        $deleteAccountForm = $this->createForm(DeleteAccountType::class, $user);
        $deleteAccountForm->handleRequest($request);
        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $security->logout(false);
            $this->em->remove($user);
            $this->em->flush();

            $request->getSession()->invalidate();

            return $this->redirectToRoute('posts.index');
        }
        return $this->render('dashboard/edit.html.twig', [
            'imageForm' => $imageForm,
            'userForm' => $userForm,
            'passwordForm' => $passwordForm,
            'deleteAccountForm' => $deleteAccountForm
        ]);
    }
}

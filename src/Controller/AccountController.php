<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account", methods="GET")
     */
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/edit", name="app_account_edit", methods={"GET", "POST"})
     */
    public function edit(EntityManagerInterface $em, Request $request): Response
    {
        $user= $this->getUser();

        $form= $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
           $em->flush();
           $this->addFlash('success', 'Account successfully updated !');

           return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig', [
            'accountForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/change-password", name="app_account_change_password", methods={"GET", "POST"})
     */
    public function changePassword(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user= $this->getUser();
        $form= $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData())
            );
            $em->flush();
            $this->addFlash('success', 'Password successfully updated !');

            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/change_password.html.twig', [
            'pswResetForm' => $form->createView()
        ]);
    }
}

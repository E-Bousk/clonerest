<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins= $pinRepository->findBy([], ['updatedAt' => 'desc'], 5);
        return $this->render('pins/index.html.twig', compact('pins'));
    }


    /**
     * @Route("/pins/create", name="pins.create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin= new Pin;
        $pin->setDescription('Description ');

        $form= $this->createFormBuilder($pin)
            ->add('title', null, [
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => "placeholder : message ici"]
                // 'required' => false
                ])
            ->add('description', null, [
                'attr' => [
                    'rows' => '5',
                    'cols' => '30',  
                ]])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('pins.show', ['id' => $pin->getId()]);
        }


        return $this->render('pins/create.html.twig', [
            'monFormulaire' => $form->createView(),
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.show", methods={"GET"})
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }



   /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="pins.edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {
        $form= $this->createFormBuilder($pin)
            ->add('title', null, [
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => "placeholder : message ici"]
                // 'required' => false
                ])
            ->add('description', null, [
                'attr' => [
                    'rows' => '5',
                    'cols' => '30',  
                ]])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            return $this->redirectToRoute('pins.show', ['id' => $pin->getId()]);
        }


        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'monFormulaire' => $form->createView(),
            
        ]);
    }


}

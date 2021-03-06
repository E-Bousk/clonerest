<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins= $pinRepository->findBy([], ['updatedAt' => 'desc'], 12);
        return $this->render('pins/index.html.twig', compact('pins'));
    }


    /**
     * @Route("/pins/create", name="pins.create", methods={"GET", "POST"})
     * @IsGranted("PIN_CREATE")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin= new Pin;
        $pin->setDescription('Lorem, ipsum dolor sit amet consectetur.');

        $form= $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created');

            // return $this->redirectToRoute('pins.show', ['id' => $pin->getId()]);
            return $this->redirectToRoute('home');
        }

        return $this->render('pins/create.html.twig', [
            'pinForm' => $form->createView(),
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
     * @Route("/pins/{id<[0-9]+>}/edit", name="pins.edit", methods={"GET", "PUT"})
     * @IsGranted("PIN_MANAGE", subject="pin", message="you cannot edit this pin")
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {
        $form= $this->createForm(PinType::class, $pin, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $this->addFlash('success', 'Pin successfully updated');

            // return $this->redirectToRoute('pins.show', ['id' => $pin->getId()]);
            return $this->redirectToRoute('home');
        }


        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'pinForm' => $form->createView(),
            
        ]);
    }


    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {
        $this->denyAccessUnlessGranted('PIN_MANAGE', $pin, 'You cannot delete this pin');

        if ($this->isCsrfTokenValid('pin.deletion'. $pin->getId(), $request->get('csrf_token')))
        {
            $em->remove($pin);            
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted');
        }

        return $this->redirectToRoute('home');
    }

}

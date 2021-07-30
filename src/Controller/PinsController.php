<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins= $pinRepository->findBy([], ['updatedAt' => 'desc'], 5);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.show", methods={"GET"}, priority="-1")
     */
    public function show(Pin $pin): Response
    {
        // dd($pin);
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}

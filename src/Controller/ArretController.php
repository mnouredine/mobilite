<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArretController extends AbstractController
{
    /**
     * @Route("/arret", name="arret")
     */
    public function index(): Response
    {
        return $this->render('arret/index.html.twig', [
            'controller_name' => 'ArretController',
        ]);
    }

    /**
     * @Route("/arrets", name="accueil_arrets")
     */
    public function accueilArrets(Request $request)
    {
        return $this->render('arret/index.html.twig', []);
    }
}

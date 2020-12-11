<?php

namespace App\Controller;

use App\Repository\PassageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArretController extends AbstractController
{
    private $passageRepository;
    private $entityManager;

    public function __construct(PassageRepository $passageRepository, EntityManagerInterface $entityManager)
    {
        $this->passageRepository = $passageRepository;
        $this->entityManager = $entityManager;
    }

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
        $passages = [];
        return $this->render('arret/index.html.twig', [
            'passages' => $passages,
        ]);
    }
}

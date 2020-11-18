<?php

namespace App\Controller;

use App\Entity\Itineraire;
use App\Form\ItineraireFormType;
use App\Repository\ItineraireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItineraireController extends AbstractController
{
    private $itineraireRepository;
    private $entityManager;

    public function __construct(ItineraireRepository $itineraireRepository, EntityManagerInterface $entityManager)
    {
        $this->itineraireRepository = $itineraireRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/itineraire_list", name="itineraire_list")
     */
    public function index(): Response
    {
        $itineraires = $this->itineraireRepository->findAll();
        return $this->render('itineraire/list-itineraire.html.twig', [
            'itineraires' => $itineraires,
        ]);
    }

    /**
     * @Route("/itineraire/ajouter", name="itineraire_ajout")
     */
    public function ajoutItineraire(Request $request): Response
    {
        $itineraire = new Itineraire();
        $form = $this->createForm(ItineraireFormType::class, $itineraire);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $this->entityManager->persist($itineraire);
            $this->entityManager->flush();
            if ($itineraire) {
                return $this->redirectToRoute('itineraire_list');
            } else {
                return $this->render('itineraire/new-itineraire.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('itineraire/new-itineraire.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

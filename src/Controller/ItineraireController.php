<?php

namespace App\Controller;

use App\Entity\Arret;
use App\Entity\Compagnie;
use App\Entity\Itineraire;
use App\Form\ArretType;
use App\Form\ItineraireFormType;
use App\Repository\ArretRepository;
use App\Repository\CompagnieRepository;
use App\Repository\ItineraireRepository;
use App\Repository\PassageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItineraireController extends AbstractController
{
    private $itineraireRepository;
    private $compagnieRepository;
    private $arretRepository;
    private $passageRepository;
    private $entityManager;

    public function __construct(ItineraireRepository $itineraireRepository, CompagnieRepository $compagnieRepository, ArretRepository $arretRepository, PassageRepository $passageRepository, EntityManagerInterface $entityManager)
    {
        $this->itineraireRepository = $itineraireRepository;
        $this->compagnieRepository = $compagnieRepository;
        $this->arretRepository = $arretRepository;
        $this->passageRepository = $passageRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/itineraire", name="itineraire_liste_base")
     * @Route("/itineraire/liste", name="itineraire_liste")
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
                return $this->redirectToRoute('itineraire_liste');
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

    /**
     * @Route("/itineraire/arret/{compagnie_id}/{itineraire_id}", name="itineraire_arret")
     */
    public function itineraireArret(Request $request, int $compagnie_id = 0, int $itineraire_id = 0): Response
    {
        $compagnies = $this->compagnieRepository->findAll();
        $itineraires = [];
        $arrets = [];
        $passages = [];
        if ($compagnie_id > 0 && $itineraire_id == 0) {
            $compagnie = $this->compagnieRepository->findOneBy(['id' => $compagnie_id]);
            $itineraires = $this->itineraireRepository->findBy(['compagnie' => $compagnie]);
            // return $this->redirectToRoute('itineraire_arret', ['compagnie_id' => $compagnie_id, 'itineraire_id' => $itineraire_id]);
        } else if ($compagnie_id > 0 && $itineraire_id > 0) {
            $compagnie = $this->compagnieRepository->findOneBy(['id' => $compagnie_id]);
            $itineraires = $this->itineraireRepository->findBy(['compagnie' => $compagnie]);
            $itineraire_selected = $this->itineraireRepository->findOneBy(['id' => $itineraire_id]);
            $arrets = $this->arretRepository->findBy(['itineraire' => $itineraire_selected]);
            $passages = $this->passageRepository->findBy(['compagnie' => $compagnie]);
        }
        $arret = new Arret();
        $form = $this->createForm(ArretType::class, $arret);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $itineraire_selected = $this->itineraireRepository->findOneBy(['id' => $itineraire_id]);
            $arret->setItineraire($itineraire_selected);
            $this->entityManager->persist($arret);
            $this->entityManager->flush();
            $arrets = $this->arretRepository->findBy(['itineraire' => $itineraire_selected]);
            return $this->redirectToRoute('itineraire_arret', ['compagnie_id' => $compagnie_id, 'itineraire_id' => $itineraire_id]);
        }
        return $this->render('itineraire/point-arret.html.twig', [
            'form' => $form->createView(),
            'compagnies' => $compagnies,
            'itineraires' => $itineraires,
            'arrets' => $arrets,
            'compagnie_id' => $compagnie_id,
            'itineraire_id' => $itineraire_id,
            'passages' => $passages,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Arret;
use App\Entity\Itineraire;
use App\Form\ArretType;
use App\Form\ItineraireFormType;
use App\Repository\ArretRepository;
use App\Repository\CompagnieRepository;
use App\Repository\ItineraireRepository;
use App\Repository\PassageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/itineraire", name="itineraire",  methods={"POST"})
     */
    public function itineraire(Request $request)
    {
        $itineraires = [];
        $emptyField = false;
        $from = $request->get('field_from');
        $to = $request->get('field_to');
        if ($from === '' && $to === '') {
            $emptyField = true;
            return $this->render('itineraire/result-itineraire.html.twig', [
                'itineraires' => $itineraires,
                'emptyField' => $emptyField,
            ]);
        }
        $arrets = $this->arretRepository->findAll();
        $localite = null;
        foreach ($arrets as $arret) {
            $localite = $arret->getPassage()->getLocalite();
            if (strpos($localite->getNom(), $from) !== false  || strpos($localite->getDescription(), $from) !== false || strpos($localite->getNom(), $to) !== false || strpos($localite->getDescription(), $to) !== false) {
                if (!$this->contains($itineraires, $arret->getItineraire())) array_push($itineraires, $arret->getItineraire());
            }
        }
        return $this->render('itineraire/result-itineraire.html.twig', [
            'itineraires' => $itineraires,
            'emptyField' => $emptyField
        ]);
    }

    /**
     * @Route("/itineraire/recherche", name="recherche_itineraire",  methods={"POST"})
     */
    public function rechercheItineraire(Request $request)
    {
        $itineraires = [];
        $emptyField = false;
        $from = $request->get('field_from');
        $to = $request->get('field_to');
        if ($from === '' && $to === '') {
            $emptyField = true;
            return $this->render('itineraire/result-itineraire.html.twig', [
                'itineraires' => $itineraires,
                'emptyField' => $emptyField,
            ]);
        }
        $arrets = $this->arretRepository->findAll();
        $localite = null;
        foreach ($arrets as $arret) {
            $localite = $arret->getPassage()->getLocalite();
            if (strpos($localite->getNom(), $from) !== false  || strpos($localite->getDescription(), $from) !== false || strpos($localite->getNom(), $to) !== false || strpos($localite->getDescription(), $to) !== false) {
                if (!$this->contains($itineraires, $arret->getItineraire())) array_push($itineraires, $arret->getItineraire());
            }
        }
        return $this->render('itineraire/result-itineraire.html.twig', [
            'itineraires' => $itineraires,
            'emptyField' => $emptyField
        ]);
    }

    /**
     * @Route("/admin/itineraire", name="itineraire_liste_base")
     * @Route("/admin/itineraire/liste", name="itineraire_liste")
     */
    public function listItineraire(): Response
    {
        $itineraires = $this->itineraireRepository->findAll();
        return $this->render('itineraire/list-itineraire.html.twig', [
            'itineraires' => $itineraires,
        ]);
    }

    /**
     * @Route("/admin/itineraire/ajouter", name="itineraire_ajout")
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
     * @Route("/admin/itineraire/modifier/{id}", name="itineraire_modification")
     */
    public function modifierItineraire(Request $request, int $id): Response
    {
        $itineraire = $this->itineraireRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(ItineraireFormType::class, $itineraire);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($itineraire);
            $this->entityManager->flush();
            if ($itineraire) {
                return $this->redirectToRoute('itineraire_liste');
            } else {
                return $this->render('itineraire/modifier-itineraire.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('itineraire/modifier-itineraire.html.twig', [
            'form' => $form->createView(),
            'itineraire' => $itineraire,
        ]);
    }

    /**
     * @Route("/admin/itineraire/arret/{compagnie_id}/{itineraire_id}", name="itineraire_arret")
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

    /**
     * @Route("/admin/itineraire/recherche/{from}/{to}", name="itineraire_recherche")
     */
    public function itineraireSearch(Request $request, string $from = '', string $to = ''): Response
    {
        $arrets = $this->arretRepository->findAll();
        $itineraires = [];
        $localite = null;
        foreach ($arrets as $arret) {
            $localite = $arret->getPassage()->getLocalite();
            if (strpos($localite->getNom(), $from) !== false  || strpos($localite->getDescription(), $from) !== false || strpos($localite->getNom(), $to) !== false || strpos($localite->getDescription(), $to) !== false) {
                if (!$this->contains($itineraires, $arret->getItineraire())) array_push($itineraires, $arret->getItineraire());
            }
        }
        $itineraire_clean = [];
        return $this->render('accueil/result-itineraire.html.twig', [
            'itineraires' => $itineraires,
        ]);
    }

    /**
     * @Route("/itineraire/infos/{id}", name="itineraire_infos")
     */
    public function infosItineraire(int $id): Response
    {
        $itineraire = $this->itineraireRepository->findOneBy(['id' => $id]);
        $arrets = $this->arretRepository->findBy(['itineraire' => $itineraire]);
        return $this->render('itineraire/infos-itineraire.html.twig', [
            'itineraire' => $itineraire,
            'arrets' => $arrets,
        ]);
    }

    public function contains($arr, Itineraire $itin): bool
    {
        foreach ($arr as $el)  if ($el->getId() === $itin->getId()) return true;
        return false;
    }
}

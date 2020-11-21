<?php

namespace App\Controller;

use App\Entity\Compagnie;
use App\Entity\Itineraire;
use App\Form\ItineraireFormType;
use App\Repository\ArretRepository;
use App\Repository\CompagnieRepository;
use App\Repository\ItineraireRepository;
use App\Repository\PassageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
    private $entityManager;

    public function __construct(ItineraireRepository $itineraireRepository, CompagnieRepository $compagnieRepository, ArretRepository $arretRepository, EntityManagerInterface $entityManager)
    {
        $this->itineraireRepository = $itineraireRepository;
        $this->compagnieRepository = $compagnieRepository;
        $this->arretRepository = $arretRepository;
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

    /**
     * @Route("/itineraire/arret/{compagnie_id}", name="itineraire_arret")
     */
    public function itineraireArret(Request $request, int $compagnie_id = 0): Response
    {
        $itineraires_list = [];
        if ($compagnie_id > 0) {
            $compagnie = $this->compagnieRepository->findOneBy(['id' => $compagnie_id]);
            $itineraires = $this->itineraireRepository->findBy(['compagnie' => $compagnie]);
            foreach ($itineraires as $itineraire) $itineraires_list[$itineraire->getCode() . ' - ' . $itineraire->getNom()] = $itineraire->getId();
            $form = $this->createFormBuilder()
                ->add('compagnie', EntityType::class, [
                    'class'        => Compagnie::class,
                    'multiple'     => false,
                    'choice_label' => 'nom',
                ])
                ->add('itineraire', ChoiceType::class, [
                    'choices' => $itineraires_list
                ])
                ->add('save', SubmitType::class, ['label' => 'Rafraichir'])
                ->getForm();
            return $this->render('itineraire/point-arret.html.twig', [
                'form' => $form->createView(),
                'arrets' => [],
                'comp' => '',
                'value' => '',
            ]);
        }
        $form = $this->createFormBuilder()
            ->add('compagnie', EntityType::class, [
                'class'        => Compagnie::class,
                'multiple'     => false,
                'choice_label' => 'nom',
            ])
            ->add('itineraire', ChoiceType::class, [
                'choices' => $itineraires_list
            ])
            ->add('save', SubmitType::class, ['label' => 'Rafraichir'])
            ->getForm();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            return new Response('long de: ' . $form->getData()['itineraire']);
            if ($form->getData()['itineraire'] == null) {
                $compagnie = $this->compagnieRepository->findOneBy(['id' => $form->getData()['compagnie']->getId()]);
                $itineraires = $this->itineraireRepository->findBy(['compagnie' => $compagnie]);
                foreach ($itineraires as $itineraire) $itineraires_list[$itineraire->getCode() . ' - ' . $itineraire->getNom()] = $itineraire->getId();
                $form = $this->createFormBuilder()
                    ->add('compagnie', EntityType::class, [
                        'class'        => Compagnie::class,
                        'multiple'     => false,
                        'choice_label' => 'nom',
                    ])
                    ->add('itineraire', ChoiceType::class, [
                        'choices' => $itineraires_list
                    ])
                    ->add('save', SubmitType::class, ['label' => 'Rafraichir'])
                    ->getForm();
                return $this->render('itineraire/point-arret.html.twig', [
                    'form' => $form->createView(),
                    'arrets' => [],
                    'comp' => '',
                    'value' => '',
                ]);
            } else {
                $itineraire_selected = $this->itineraireRepository->findOnBy(['id' => $form->getData()['itineraire']]);
                $arrets = $this->arretRepository->findBy(['itineraire' => $itineraire_selected]);
                // return $this->render('itineraire/point-arret.html.twig', [
                //     'form' => $form->createView(),
                //     'arrets' => $arrets,
                //     'comp' => '',
                //     'value' => '',
                // ]);
                return new Response('long de: ' . $form->getData()['itineraire']);
            }
        }
        return $this->render('itineraire/point-arret.html.twig', [
            'form' => $form->createView(),
            'arrets' => [],
            'comp' => '',
            'value' => '',
        ]);
    }
}

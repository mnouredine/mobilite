<?php

namespace App\Controller;

use App\Entity\Passage;
use App\Form\PassageType;
use App\Repository\PassageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PassageController extends AbstractController
{
    private $passageRepository;
    private $entityManager;

    public function __construct(PassageRepository $passageRepository, EntityManagerInterface $entityManager)
    {
        $this->passageRepository = $passageRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/passage", name="passage")
     */
    public function index(): Response
    {
        return $this->render('passage/index.html.twig', [
            'controller_name' => 'PassageController',
        ]);
    }

    /**
     * @Route("/passage/list", name="passage_list")
     */
    public function listPassages(): Response
    {
        $passages = $this->passageRepository->findAll();
        return $this->render('passage/list-passage.html.twig', [
            'passages' => $passages,
        ]);
    }

    /**
     * @Route("/passage/ajouter", name="passage_ajout")
     */
    public function ajouterPassages(Request $request): Response
    {
        $passage = new Passage();
        $form = $this->createForm(PassageType::class, $passage);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($passage);
            $this->entityManager->flush();
            if ($passage) {
                return $this->redirectToRoute('passage_list');
            } else {
                return $this->render('passage/creer-passage.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('passage/creer-passage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/passage/modifier/{id}", name="passage_modifier")
     */
    public function modifierPassages(Request $request, int $id): Response
    {
        $passage = $this->passageRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(PassageType::class, $passage);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($passage);
            $this->entityManager->flush();
            if ($passage) {
                return $this->redirectToRoute('passage_list');
            } else {
                return $this->render('passage/modifier-passage.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('passage/modifier-passage.html.twig', [
            'form' => $form->createView(),
            'nomPassage' => $passage->getNom(),
        ]);
    }
}

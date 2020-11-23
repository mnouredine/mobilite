<?php

namespace App\Controller;

use App\Entity\Compagnie;
use App\Form\CompagnieType;
use App\Repository\CompagnieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompagnieController extends AbstractController
{
    private $compagnieRepository;
    private $entityManager;

    public function __construct(CompagnieRepository $compagnieRepository, EntityManagerInterface $entityManager)
    {
        $this->compagnieRepository = $compagnieRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/compagnie", name="compagnie_liste_base")
     * @Route("/compagnie/liste", name="compagnie_liste")
     */
    public function listCompagnie(): Response
    {
        $compagnies = $this->compagnieRepository->findAll();
        return $this->render('compagnie/list-compagnie.html.twig', [
            'compagnies' => $compagnies,
        ]);
    }

    /**
     * @Route("/compagnie/ajouter", name="compagnie_ajout")
     */
    public function ajoutCompagnie(Request $request): Response
    {
        $compagnie = new Compagnie();
        $form = $this->createForm(CompagnieType::class, $compagnie);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($compagnie);
            $this->entityManager->flush();
            if ($compagnie) {
                return $this->redirectToRoute('compagnie_liste');
            } else {
                return $this->render('compagnie/creer-compagnie.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('compagnie/creer-compagnie.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compagnie/modifier/{id}", name="compagnie_modification")
     */
    public function modifierCompagnie(Request $request, int $id): Response
    {
        $compagnie = $this->compagnieRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(CompagnieType::class, $compagnie);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($compagnie);
            $this->entityManager->flush();
            if ($compagnie) {
                return $this->redirectToRoute('compagnie_liste');
            } else {
                return $this->render('compagnie/creer-compagnie.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('compagnie/creer-compagnie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

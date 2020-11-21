<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    private $documentRepository;
    private $entityManager;

    public function __construct(DocumentRepository $documentRepository, EntityManagerInterface $entityManager)
    {
        $this->documentRepository = $documentRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/document", name="accueil_document")
     */
    public function index(): Response
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    /**
     * @Route("/document/list", name="document_list")
     */
    public function listeDocuments(): Response
    {
        $documents = $this->documentRepository->findAll();
        return $this->render('document/list-document.html.twig', [
            'documents' => $documents,
        ]);
    }

    /**
     * @Route("/document/ajouter", name="document_ajout")
     */
    public function ajouterDocument(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($document);
            $this->entityManager->flush();
            if ($document) {
                return $this->redirectToRoute('document_list');
            } else {
                return $this->render('document/creer-document.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('document/creer-document.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/document/modifier/{id}", name="document_modification")
     */
    public function modifierDocument(Request $request, int $id): Response
    {
        $document = $this->documentRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(DocumentType::class, $document);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $this->entityManager->persist($document);
            $this->entityManager->flush();
            if ($document) {
                return $this->redirectToRoute('document_list');
            } else {
                return $this->render('document/modifier-document.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('document/modifier-document.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/document/details/{id}", name="document_details")
     */
    public function detailsDocument(Request $request, int $id): Response
    {
        $document = $this->documentRepository->findOneBy(['id' => $id]);
        return $this->render('document/details-document.html.twig', [
            'document' => $document,
        ]);
    }
}

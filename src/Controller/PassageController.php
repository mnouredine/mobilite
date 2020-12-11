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
     * @Route("/admin/passage/list", name="passage_list")
     */
    public function listPassages(): Response
    {
        $passages = $this->passageRepository->findAll();
        return $this->render('passage/list-passage.html.twig', [
            'passages' => $passages,
        ]);
    }

    /**
     * @Route("/admin/passage/ajouter", name="passage_ajout")
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
     * @Route("/admin/passage/modifier/{id}", name="passage_modifier")
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

    /**
     * @Route("/passage/autours/{coord}", name="passage_autours")
     */
    public function listArretsAutours(string $coord): Response
    {
        $passageAll = $this->passageRepository->findAll();
        $passages = [];
        // $dist = $this->distance($this->getLat($coord), $this->getlng($coord), $this->getLat($passages[0]->getCoordonnees()), $this->getLng($passages[0]->getCoordonnees()), 'K');
        // return new Response($dist);
        $dist = 0;
        foreach ($passageAll as $passage) {
            $dist = $this->distance($this->getLat($coord), $this->getlng($coord), $this->getLat($passage->getCoordonnees()), $this->getLng($passage->getCoordonnees()), 'K');
            if ($dist <= $_ENV['NEARBY_DISTANCE']) array_push($passages, $passage);
        }
        return $this->render('passage/passages-autours.html.twig', [
            'passages' => $passages,
            'currentLocation' => $coord,
        ]);
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    function getLat($latlng)
    {
        $arr = explode(',', $latlng);
        if (sizeof($arr) !== 2) return 0;
        return (float)$arr[0];
    }

    function getLng($latlng)
    {
        $arr = explode(',', $latlng);
        if (sizeof($arr) !== 2) return 0;
        return (float)$arr[1];
    }
}

<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/user", name="reset_user")
     */
    public function index(): Response
    {
        // $user = $this->userRepository->findOneBy(['id' => 1]);
        // $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        // $this->entityManager->persist($user);
        // $this->entityManager->flush();
        return new Response('OK');
    }
}

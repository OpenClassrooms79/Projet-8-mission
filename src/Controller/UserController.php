<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/employes', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $userRepository->findAll(),
        ]);
    }
}
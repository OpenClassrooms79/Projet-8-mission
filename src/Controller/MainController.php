<?php

namespace App\Controller;

use App\Entity\Status;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }
}

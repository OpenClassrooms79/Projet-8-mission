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

    #[Route('/projets/{id}', name: 'app_project')]
    public function project(ProjectRepository $projectRepository, TaskRepository $taskRepository, UserRepository $userRepository, int $id): Response
    {
        return $this->render('project.html.twig', [
            'project' => $projectRepository->findOneBy(['id' => $id]),
            'tasks' => [
                Status::TO_DO => $taskRepository->findBy(['projectId' => $id, 'statusId' => Status::TO_DO]),
                Status::DOING => $taskRepository->findBy(['projectId' => $id, 'statusId' => Status::DOING]),
                Status::DONE => $taskRepository->findBy(['projectId' => $id, 'statusId' => Status::DONE]),
            ],
            'statuses' => Status::STATUSES,
            'users' => $userRepository->findAll(),
        ]);
    }
}

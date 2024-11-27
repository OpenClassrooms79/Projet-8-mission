<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Routing\Requirement\Requirement;

use function ksort;

class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/projets/{id}', name: 'app_project_show', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function show(ProjectRepository $projectRepository, int $id): Response
    {
        $project = $projectRepository->findOneBy(['id' => $id]);
        $sortedTasks = [];
        $tasks = $project->getTasks();
        foreach ($tasks as $task) {
            $sortedTasks[$task->getStatusId()][] = $task;
        }
        ksort($sortedTasks);

        return $this->render('project.html.twig', [
            'project' => $project,
            'tasks' => $sortedTasks,
            'statuses' => Status::STATUSES,
        ]);
    }

    #[Route('/projet/{id}/supprimer', name: 'app_project_delete', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Project $project, EntityManagerInterface $entityManager): Response
    {
        // suppression de toutes les tâches du projet
        foreach ($project->getTasks() as $task) {
            $entityManager->remove($task);
        }

        //suppression du projet
        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('app_main');
    }
}
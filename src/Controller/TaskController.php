<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TaskController extends AbstractController
{
    public const ERROR_TITLE = 'Tâche inexistante';
    public const ERROR_CREATE = "Impossible de créer la tâche car le projet n°%d n'existe pas.";
    public const ERROR_EDIT = "Impossible de modifier la tâche n°%d car elle n'existe pas.";
    public const ERROR_DELETE = "Impossible de supprimer la tâche n°%d car elle n'existe pas.";

    #[Route('/projet/{id}/tache/creer', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, ProjectRepository $projectRepository, int $id): Response
    {
        $project = $projectRepository->findOneBy(['id' => $id]);
        if ($project === null) {
            return $this->forward('App\Controller\ErrorController::index', [
                'title' => ProjectController::ERROR_TITLE,
                'message' => self::ERROR_CREATE,
                'id' => $id,
            ]);
        }
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($project);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }

        return $this->render('task/add.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/tache/{id}/modifier', name: 'task_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        if ($task === null) {
            return $this->forward('App\Controller\ErrorController::index', [
                'title' => self::ERROR_TITLE,
                'message' => self::ERROR_EDIT,
                'id' => $id,
            ]);
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project_show', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/tache/{id}/supprimer', name: 'task_delete', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(EntityManagerInterface $entityManager, TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        if ($task === null) {
            return $this->forward('App\Controller\ErrorController::index', [
                'title' => self::ERROR_TITLE,
                'message' => self::ERROR_DELETE,
                'id' => $id,
            ]);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        $project = $task->getProject();
        if ($project === null) {
            return $this->redirectToRoute('project_index');
        }
        return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
    }
}

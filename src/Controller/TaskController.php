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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TaskController extends AbstractController
{
    #[Route('/projet/{id}/tache/creer', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, ProjectRepository $projectRepository, int $id): Response
    {
        $project = $projectRepository->findOneBy(['id' => $id]);
        if ($project === null) {
            throw new NotFoundHttpException('Ce projet n\'existe pas.');
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
            'controller_name' => 'TaskController',
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/tache/{id}/modifier', name: 'task_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        if ($task === null) {
            throw new NotFoundHttpException('Cette tâche n\'existe pas.');
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
            'controller_name' => 'TaskController',
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/tache/{id}/supprimer', name: 'task_delete', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(EntityManagerInterface $entityManager, TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        if ($task === null) {
            throw new NotFoundHttpException('Cette tâche n\'existe pas.');
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

<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TaskController extends AbstractController
{
    #[Route('/tache/{id}/modifier', name: 'app_task_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'controller_name' => 'TaskController',
            'form' => $form,
            'task' => $task,
        ]);
    }
}

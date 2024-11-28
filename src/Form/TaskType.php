<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('deadline', DateType::class, ['label' => 'Date'])
            ->add('statusId', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_flip(Status::STATUSES),
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Membre',
                'choice_label' => function (User $user) {
                    return $user->getFullName();
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}

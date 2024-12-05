<?php

namespace App\Form;

use App\Entity\User;
use App\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
                'data' => $options['data']['task']->getTitle(), // valeur par défaut
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'data' => $options['data']['task']->getDescription(), // valeur par défaut
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date',
                'data' => $options['data']['task']->getDeadline(), // valeur par défaut
            ])
            ->add('statusId', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => array_flip(Status::STATUSES),
                'data' => $options['data']['task']->getStatusId(), // valeur par défaut
            ])->add('user', ChoiceType::class, [
                'label' => 'Membre',
                'placeholder' => '--- Non assigné ---',
                'required' => false,
                'choice_label' => function (User $user) {
                    return $user->getFullName();
                },
                'choices' => $options['data']['users'], // liste des valeurs possibles
                'data' => $options['data']['task']->getUser(), // valeur par défaut
                /*'constraints' => [
                    new Assert\Expression([
                        'expression' => 'this["statusId"] == 1 or value !== null',
                        'message' => 'User is required unless statusId is 1',
                    ]),
                ],*/
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}

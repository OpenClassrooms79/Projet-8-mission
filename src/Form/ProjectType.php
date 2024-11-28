<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du projet',
                'required' => true,
                'data' => $options['data']['project']->getName(), // valeur par défaut
            ])->add('users', ChoiceType::class, [
                'label' => 'Inviter des membres',
                'multiple' => true,
                'required' => true,
                'choice_label' => function ($user) {
                    return $user->getFullName(); // le nom complet de l'utilisateur est utilisé comme intitulé pour la balise <option>
                },
                'choice_value' => function ($user) {
                    return $user->getId(); // l'id de l'utilisateur est utilisé comme valeur pour la balise <option>
                },
                'choices' => $options['data']['all_users'], // liste des valeurs possibles
                'data' => $options['data']['users'], // valeurs par défaut
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}

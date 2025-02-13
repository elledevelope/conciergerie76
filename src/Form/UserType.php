<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Email*',
            ])
            ->add('nom', null, [
                'label' => 'Nom*',
            ])
            ->add('prenom', null, [
                'label' => 'Prénom*',
            ])
            ->add('datedenaissance', null, [
                'label' => 'Date de naissance*',
                'widget' => 'single_text',
            ])
            ->add('adresse', null, [
                'label' => 'Adresse*',
            ])
            // ->add('avatar')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

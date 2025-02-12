<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom*',
                'attr' => ['placeholder' => 'Votre nom'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\- ]+$/u',
                        'message' => 'Votre nom ne peut contenir que des lettres et des espaces.'
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom*',
                'attr' => ['placeholder' => 'Votre prénom'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\- ]+$/u',
                        'message' => 'Votre prénom ne peut contenir que des lettres et des espaces.'
                    ]),
                ],
            ])
            ->add('datedenaissance', DateType::class, [
                'label' => 'En soumettant ce formulaire, j’accepte que mes données soient utilisées dans le cadre de ma demande et de la relation qui peut en découler. Ces données sont collectées pour vous apporter le meilleur service et ne sont nullement vouées à être transmises à des tiers. Conformément à la loi du 6 Janvier 1978, vous disposez d\'un droit d\'accès, d\'opposition et de rectification de ces données. Pour l\'exercer, vous pouvez nous écrire à <a href="mailto:contact@conciergerie76.com">l\'administrateur du site</a>.',
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control'],
                'required' => true,
                // 'constraints' => [
                //     new IsTrue([
                //         'message' => 'You should agree to our terms.',
                //     ]),
                // ],
                'label_html' => true,
                'label_attr' => ['class' => 'agree-terms-label'],
            ])

            ->add('adresse')
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est requis.']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
                'label' => 'Email',
                'attr' => ['placeholder' => 'example@gmail.com'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'En soumettant ce formulaire, j’accepte que mes données soient utilisées dans le cadre de ma demande et de la relation qui peut en découler. Ces données sont collectées pour vous apporter le meilleur service et ne sont nullement vouées à être transmises à des tiers. Conformément à la loi du 6 Janvier 1978, vous disposez d\'un droit d\'accès, d\'opposition et de rectification de ces données. Pour l\'exercer, vous pouvez nous écrire à <a href="mailto:contact@conciergerie76.com">l\'administrateur du site</a>.',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter les conditions.']),
                ],
                'label_html' => true,
                'label_attr' => ['class' => 'agree-terms-label'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',  // Minimum 8 caractères, lettres et chiffres
                        'message' => 'Votre mot de passe doit contenir au moins une lettre et un chiffre.',
                    ]),
                ],
                'attr' => [
                    'autocomplete' => 'new-password', // Empêche le remplissage automatique avec des anciens mots de passe
                ],
            ])
        ;
        // ->add('plainPassword', PasswordType::class, [
        //     // instead of being set onto the object directly,
        //     // this is read and encoded in the controller
        //     'mapped' => false,
        //     'attr' => ['autocomplete' => 'new-password'],
        //     'constraints' => [
        //         new NotBlank([
        //             'message' => 'Please enter a password',
        //         ]),
        //         new Length([
        //             'min' => 6,
        //             'minMessage' => 'Your password should be at least {{ limit }} characters',
        //             // max length allowed by Symfony for security reasons
        //             'max' => 4096,
        //         ]),
        //     ],
        // ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

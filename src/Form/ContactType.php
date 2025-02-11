<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom*',
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
                'label' => 'Votre prénom*',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\- ]+$/u',
                        'message' => 'Votre prénom ne peut contenir que des lettres et des espaces.'
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email*',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Votre téléphone',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 20]),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9\s\-().]+$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide.',
                    ]),
                ],
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet de demande*',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 100]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Saisissez votre message*',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 10, 'max' => 2000]),
                ],
                'attr' => ['rows' => 16],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'En soumettant ce formulaire, j’accepte que mes données soient utilisées dans le cadre de ma demande et de la relation qui peut en découler.
    Ces données sont collectées pour vous apporter le meilleur service et ne sont nullement vouées à être transmises à des tiers. Conformément à la loi du 6 Janvier 1978,
    vous disposez d\'un droit d\'accès, d\'opposition et de rectification de ces données. Pour l\'exercer, vous pouvez nous écrire à
                     <a href="mailto:contact@conciergerie76.com">l\'administrateur du site</a>.',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Assert\IsTrue(['message' => 'Vous devez accepter les conditions.']),
                ],
                'label_html' => true,
            ])
            ->add('honeypot', HiddenType::class, [
                'mapped' => false,
                'attr' => ['style' => 'display:none'], // Bots tend to fill this, humans won't.
                'constraints' => [
                    new Assert\Blank(), // Must remain empty, otherwise it's a bot
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'csrf_protection' => true, // Ensures form submissions have CSRF token
        ]);
    }
}

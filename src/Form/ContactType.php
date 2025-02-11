<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            ->add('objet')
            ->add('message')
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'En soumettant ce formulaire, j’accepte que mes données soient utilisées dans le cadre de ma demande et de la relation qui peut en découler.
Ces données sont collectées pour vous apporter le meilleur service et ne sont nullement vouées à être transmises à des tiers. Conformément à la loi du 6 Janvier 1978,
vous disposez d\'un droit d\'accès, d\'opposition et de rectification de ces données. Pour l\'exercer, vous pouvez nous écrire à
                 <a href="mailto:admini@exemple.com">l\'administrateur du site</a>.',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'consentement-box',
                ],
                'row_attr' => [
                    'class' => 'consentement',
                ],
                'label_html' => true, // Autoriser l'utilisation de balises HTML dans le libellé
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

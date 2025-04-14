<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class LivraisonTrackingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('tracking_number', TextType::class, [
        'label' => 'Numéro de suivi',
        'constraints' => [
            new Regex([
                'pattern' => '/^Liv-#\d+$/',
                'message' => 'Le numéro de suivi doit être au format Liv-#XXXX',
            ]),
        ],
        'attr' => [
            'placeholder' => 'Liv-#0000',
        ]
    ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class FacturisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payment_method')
            ->add('amount', NumberType::class, [
                'label' => 'Amount',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,  // Disallow negative numbers
                    'step' => 'any'  // Allows decimal values
                ],
                'constraints' => [
                    new NotBlank(['message' => "Le montant de la facturisation ne doit pas être vide."]),
                    new Regex([
                        'pattern' => '/^\d+(\.\d{1,2})?$/',
                        'message' => "Veuillez entrer un montant valide."
                    ])
                ]
            ])
            ->add('payment_date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('status')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'userEmail',  // Ensure User entity has `getUserEmail()`
                'label' => 'User',
                'constraints' => [
                    new NotBlank([
                        'message' => 'User must be selected',
                    ])
                ],
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Facturisation::class,
        ]);
    }
}



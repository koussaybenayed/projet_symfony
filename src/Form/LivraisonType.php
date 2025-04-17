<?php

// src/Form/LivraisonType.php
namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estimated_delivery', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de livraison estimée',
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'help' => 'La date doit être dans le futur',
                'required' => true,
            ])
            ->add('delivery_cost', NumberType::class, [
                'label' => 'Coût de livraison (€)',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'step' => '0.01',
                ],
                'required' => true,
            ])
            ->add('poids_colis', NumberType::class, [
                'label' => 'Poids du colis (kg)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 20,
                ],
                'help' => 'Le poids maximum autorisé est de 20 kg',
            ])
            ->add('destination_status', ChoiceType::class, [
                'label' => 'Statut de destination',
                'required' => true,
                'choices' => [
                    'En attente' => 'en_attente',
                    'En cours' => 'en_cours',
                    'Livré' => 'livre',
                    'Annulé' => 'annule',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Assistance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AssistanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeAssistance', ChoiceType::class, [
                'choices' => [
                    'Fauteuil roulant' => 'fauteuil roulant',
                    'Accompagnement' => 'accompagnement',
                    'Assistance auditive' => 'assistance auditive',
                    'Aide à l’embarquement' => 'aide à l’embarquement',
                    'Aide aux bagages' => 'aide aux bagages',
                    'Guidage pour malvoyants' => 'guidage pour malvoyants',
                    'Assistance médicale' => 'assistance médicale',
                ],
                'placeholder' => 'Choisissez un type d’assistance',
                'required' => false,
            ])
            ->add('aeroportPort', TextType::class, [
                'required' => false,
            ])
            ->add('heurePriseEnCharge', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('pointRendezVous', ChoiceType::class, [
                'choices' => [
                    'Hall principal' => 'hall principal',
                    'Porte d’embarquement' => 'porte d’embarquement',
                    'Accueil PMR' => 'accueil PMR',
                    'Zone d’enregistrement' => 'zone d’enregistrement',
                    'Sortie de douane' => 'sortie de douane',
                ],
                'placeholder' => 'Choisissez un point de rendez-vous',
                'required' => false,
            ])
            ->add('informationsComplementaires', TextType::class, [
                'required' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Demandé' => 'demandé',
                    'En cours' => 'en cours',
                    'Completé' => 'completé',
                    'Annulé' => 'annulé',
                ],
                'placeholder' => 'Choisissez un statut',
                'required' => false,
            ])
            ->add('billet', HiddenType::class, [
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assistance::class,
        ]);
    }
}

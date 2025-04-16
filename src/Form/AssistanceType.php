<?php

namespace App\Form;
use App\Entity\Billet;
use App\Entity\Assistance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class AssistanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_assistance', ChoiceType::class, [
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
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir un type d’assistance.']),
                ],
            ])
            ->add('aeroport_port', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner l’aéroport ou le port.']),
                ],
            ])
            ->add('heure_prise_en_charge', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner l’heure de prise en charge.']),
                    new GreaterThanOrEqual([
                        'value' => 'now',
                        'message' => 'L’heure de prise en charge doit être à partir d’aujourd’hui.',
                    ]),
                ],
            ])
            ->add('point_rendez_vous', ChoiceType::class, [
                'choices' => [
                    'Hall principal' => 'hall principal',
                    'Porte d’embarquement' => 'porte d’embarquement',
                    'Accueil PMR' => 'accueil PMR',
                    'Zone d’enregistrement' => 'zone d’enregistrement',
                    'Sortie de douane' => 'sortie de douane',
                ],
                'placeholder' => 'Choisissez un point de rendez-vous',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir un point de rendez-vous.']),
                ],
            ])
            ->add('informations_complementaires', null, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner les informations complémentaires.']),
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Demandé' => 'demandé',
                    'En cours' => 'en cours',
                    'Completé' => 'completé',
                    'Annulé' => 'annulé',
                ],
                'placeholder' => 'Choisissez un statut',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir un statut.']),
                ],
            ])
            ->add('billet', HiddenType::class, [
                'mapped' => false, // Ce champ ne sera pas mappé directement à l'entité Assistance
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assistance::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Reduction;
use App\Entity\Vehicule;
use App\Entity\Assistance;
use App\Entity\Billet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateArrive', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullname', // ou 'email'
                'placeholder' => 'Sélectionnez un utilisateur'
            ])
            ->add('reduction', EntityType::class, [
                'class' => Reduction::class,
                'choice_label' => 'code',
                'placeholder' => 'Sélectionnez une réduction',
                'required' => false
            ])
            ->add('compagnie', null, [])
            ->add('depart', null, [])
            ->add('destination', null, [])
            ->add('prix', null, [])
            ->add('classe', ChoiceType::class, [
                'choices' => [
                    'Economie' => 'Economie',
                    'Business' => 'Business',
                    'Première' => 'Première'
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Réservé' => 'Réservé',
                    'Annulé' => 'Annulé',
                    'Confirmé' => 'Confirmé'
                ]
            ])
            ->add('typeTransport', ChoiceType::class, [
                'choices' => [
                    'Avion' => 'Avion',
                    'Bateau' => 'Bateau'
                ],
                'placeholder' => 'Choisissez un type de transport'
            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'id', // ou autre champ
                'placeholder' => 'Sélectionnez un véhicule',
                'required' => false
            ])
            ->add('assistance', EntityType::class, [
                'class' => Assistance::class,
                'choice_label' => 'id', // ou autre champ
                'placeholder' => 'Choisissez une assistance',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,
        ]);
    }
}
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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Regex;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date de départ est obligatoire.']),
                   
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateArrive', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date d\'arrivée est obligatoire.']),
                    new Assert\Type(['type' => \DateTimeInterface::class])
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'user_email', // ou 'email'
                'placeholder' => 'Sélectionnez un utilisateur',
                'constraints' => [
                    new Assert\NotNull(['message' => 'Veuillez sélectionner un utilisateur.'])
                ]
            ])
            ->add('reduction', EntityType::class, [
                'class' => Reduction::class,
                'choice_label' => 'code',
                'placeholder' => 'Sélectionnez une réduction',
                'required' => false
            ])
            ->add('compagnie', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La compagnie est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La compagnie ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('depart', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le lieu de départ est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le lieu de départ ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('destination', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La destination est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'La destination ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('prix', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix est requis.']),
                    new Assert\Positive(['message' => 'Le prix doit être un nombre positif.'])
                ]
            ])
            ->add('classe', ChoiceType::class, [
                'choices' => [
                    'Economie' => 'Economie',
                    'Business' => 'Business',
                    'Première' => 'Première'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La classe est obligatoire.']),
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Réservé' => 'Réservé',
                    'Annulé' => 'Annulé',
                    'Confirmé' => 'Confirmé'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le statut est obligatoire.']),
                ]
            ])
            ->add('typeTransport', ChoiceType::class, [
                'choices' => [
                    'Avion' => 'Avion',
                    'Bateau' => 'Bateau'
                ],
                'placeholder' => 'Choisissez un type de transport',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type de transport est obligatoire.']),
                ]
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

<?php
namespace App\Form;

use App\Entity\ControleDouanier;
use App\Entity\Livraison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ControleDouanierType extends AbstractType
{
   
    private const PAYS_VALIDES = [
        'France', 'Belgique', 'Suisse', 'Allemagne', 'Espagne', 'Italie', 
        'Royaume-Uni', 'Pays-Bas', 'Luxembourg', 'Portugal', 'Autriche',
        'Danemark', 'Suède', 'Finlande', 'Norvège', 'Irlande', 'Grèce',
        'Pologne', 'République Tchèque', 'Slovaquie', 'Hongrie', 'Roumanie',
        'Bulgarie', 'Croatie', 'Slovénie', 'États-Unis', 'Canada', 'Japon',
        'Chine', 'Australie', 'Nouvelle-Zélande', 'Brésil', 'Argentine',
        'Maroc', 'Tunisie', 'Algérie', 'Sénégal', 'Côte d\'Ivoire'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livraison', EntityType::class, [
                'class' => Livraison::class,
                'choice_label' => function (Livraison $livraison) {
                    return 'Colis #' . $livraison->getId_livraisons() . ' - ' . $livraison->getPoidsColis() . ' kg';
                },
                'label' => 'Colis à contrôler',
                'placeholder' => 'Sélectionnez un colis',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un colis pour le contrôle douanier',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-select',
                    'required' => 'required',
                ],
            ])
            ->add('pays_douane', TextType::class, [
                'label' => 'Pays de douane',
                'constraints' => [
                    new NotBlank([
                        'message' => '',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le pays doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'Le pays ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s\-\']+$/',
                        'message' => 'Le pays ne doit contenir que des lettres, espaces, apostrophes et tirets',
                    ]),
                    new Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            if (empty($value)) {
                                return;
                            }
                            
                            // Vérification insensible à la casse
                            $paysNormalise = mb_strtolower(trim($value));
                            $paysValidesNormalises = array_map('mb_strtolower', self::PAYS_VALIDES);
                            
                            if (!in_array($paysNormalise, $paysValidesNormalises)) {
                                $context->buildViolation("Ce pays n'est pas reconnu dans notre liste de pays valides pour les contrôles douaniers")
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: France, Belgique, Suisse...',
                    'required' => 'required',
                    'pattern' => '^[a-zA-ZÀ-ÿ\s\-\']+$',
                    'minlength' => '2',
                    'maxlength' => '50',
                    'list' => 'pays-list',
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Validé' => 'Validé',
                    'Rejeté' => 'Rejeté',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => '',
                    ]),
                    new Choice([
                        'choices' => ['En attente', 'En cours', 'Validé', 'Rejeté'],
                        'message' => 'Le statut sélectionné n\'est pas valide',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-select',
                    'required' => 'required',
                ],
            ])
            ->add('date_controle', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de contrôle',
                'constraints' => [
                    new NotBlank([
                        'message' => '',
                    ]),
                    new Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            if (empty($value)) {
                                return;
                            }
                            
                            $today = new \DateTime();
                            $today->setTime(0, 0, 0, 0); // Minuit aujourd'hui
                            
                            if ($value <= $today) {
                                $context->buildViolation('La date de contrôle doit être dans le futur (pas aujourd\'hui ni dans le passé)')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                    'min' => (new \DateTime('+1 day'))->format('Y-m-d'),
                ],
            ])
            ->add('commentaires', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => '',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Commentaires optionnels sur le contrôle douanier...',
                    'maxlength' => '50',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ControleDouanier::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
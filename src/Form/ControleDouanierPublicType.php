<?php

namespace App\Form;

use App\Entity\ControleDouanier;
use App\Entity\Livraison;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ControleDouanierPublicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('livraison', EntityType::class, [
                'class' => Livraison::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        // Montrer uniquement les livraisons sans contrôle douanier existant ou en attente/rejeté
                        ->leftJoin('l.controleDouanier', 'c')
                        ->where('c.id_controle IS NULL OR c.statut = :statut1 OR c.statut = :statut2')
                        ->setParameter('statut1', 'En attente')
                        ->setParameter('statut2', 'Rejeté');
                },
                'choice_label' => function(Livraison $livraison) {
                    return sprintf('#%s - %s kg - %s', 
                       
                        $livraison->getPoidsColis(), 
                        $livraison->getDestinationStatus()
                    );
                },
                'placeholder' => 'Sélectionnez un colis',
                'required' => true,
                'attr' => [
                    'class' => 'form-select custom-select2'
                ],
                'label' => 'Colis à contrôler'
            ])
            ->add('pays_douane', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'list' => 'pays-list',
                    'placeholder' => 'Saisissez le pays de destination'
                ],
                'label' => 'Pays de douane',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le pays de douane']),
                ],
            ])
            ->add('date_controle', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
                'label' => 'Date souhaitée pour le contrôle',
                'required' => true,
            ])
            ->add('commentaires', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'maxlength' => 50
                ],
                'label' => 'Commentaires ou instructions particulières',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'Les commentaires ne peuvent pas dépasser {{ limit }} caractères'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ControleDouanier::class,
        ]);
    }
}
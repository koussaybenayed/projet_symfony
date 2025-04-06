<?php

namespace App\Form;

use App\Entity\ControleDouanier;
use App\Entity\Livraison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleDouanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pays_douane')
            ->add('statut')
            ->add('date_controle', null, [
                'widget' => 'single_text'
            ])
            ->add('commentaires')
            ->add('latitude')
            ->add('longitude')
            ->add('livraison', EntityType::class, [
                'class' => Livraison::class,
'choice_label' => 'id',
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

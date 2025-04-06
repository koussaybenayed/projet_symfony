<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estimated_delivery', null, [
                'widget' => 'single_text'
            ])
            ->add('delivery_cost')
            ->add('created_at', null, [
                'widget' => 'single_text'
            ])
            ->add('poids_colis')
            ->add('destination_status')
            ->add('transporteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}

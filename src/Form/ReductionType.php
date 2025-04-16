<?php

namespace App\Form;

use App\Entity\Reduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;  // Add this import
use Symfony\Component\Form\Extension\Core\Type\NumberType; 

class ReductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('discountAmount', NumberType::class, [
                'required' => true,
                'scale' => 2, // Nombre de décimales
                'html5' => true, // Assurez-vous que le champ est de type number dans le HTML
                'invalid_message' => "Veuillez entrer un nombre valide.", // Message d'erreur personnalisé
            ])
            
            ->add('expiration_date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
           
            ->add('condition_text')
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reduction::class,
        ]);
    }
}
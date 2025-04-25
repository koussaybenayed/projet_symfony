<?php

namespace App\Form;

use App\Entity\Reduction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code 🔑',
                'attr' => [
                    'placeholder' => 'Ex: NAVI2024',
                    'class' => 'form-control'
                ]
            ])
            ->add('discountAmount', NumberType::class, [
                'label' => 'Montant de Réduction 💸',
                'required' => true,
                'scale' => 2,
                'html5' => true,
                'invalid_message' => "Veuillez entrer un nombre valide.",
                'attr' => [
                    'placeholder' => 'Ex: 15.00',
                    'class' => 'form-control'
                ]
            ])
            ->add('expiration_date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date d\'expiration ⏳',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('condition_text', TextType::class, [
                'label' => 'Condition 📜',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Valable à partir de 50€ d\'achat',
                    'class' => 'form-control',
                ]
            ])
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date de création 📝',
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

<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Enter description here...'
                     
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Description cannot be blank'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 5000,
                        'minMessage' => 'Description must be at least {{ limit }} characters',
                        'maxMessage' => 'Description cannot exceed {{ limit }} characters'
                    ])
                ]
            ])
            ->add('date_reclamation', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    

                    
                ],
                'empty_data' => null,
                'constraints' => [
                    new GreaterThan([
                        'value' => 'yesterday',
                        'message' => 'The date cannot be in the past'
                    ])
                ]
            ])
            ->add('status', TextareaType::class, [
                'label' => 'Status',
                'attr' => [
                    'rows' => 3,
                    'class' => 'form-control',
                    'placeholder' => 'Enter status here...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Status cannot be blank'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 5000,
                        'minMessage' => 'Status must be at least {{ limit }} characters',
                        'maxMessage' => 'Status cannot exceed {{ limit }} characters'
                    ])
                ]
            ])
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class, 
        ]);
    }
}   
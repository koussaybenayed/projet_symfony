<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('response_text', TextareaType::class, [
                'label' => 'response_text',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Enter response text'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'response text cannot be blank'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 20,
                        'minMessage' => 'Description must be at least {{ limit }} characters',
                        'maxMessage' => 'Description cannot exceed {{ limit }} characters'
                    ])
                ]
            ])
            ->add('created_at', null, [
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
                ->add('reclamation', EntityType::class, [
                    'class' => Reclamation::class,
                    'choice_label' => 'description',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}

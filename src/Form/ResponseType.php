<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Get the Response object from options
        $response = $options['data'];

        // Add the response_text field
        $builder
            ->add('response_text', TextareaType::class, [
                'label' => 'Response Text',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Enter response text'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Response text cannot be blank'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
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
            ]);

        // Conditionally add the 'reclamation' field if no reclamation exists for the response
        if (!$response->getReclamation()) {
            // If no reclamation is set on the response, show the 'reclamation' field
            $builder->add('reclamation', EntityType::class, [
                'class' => Reclamation::class,
                'choice_label' => 'description',
                'placeholder' => 'Select a Reclamation',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
        }
        // If a reclamation is already associated, do not show the 'reclamation' field at all
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}

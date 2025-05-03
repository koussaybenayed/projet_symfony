<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_username', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9_]{3,20}$/',
                        'message' => 'Username must be 3-20 characters long and contain only letters, numbers, or underscores.',
                    ]),
                ]
            ])
            ->add('user_email', TextType::class, [
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Please enter a valid email address.',
                    ])
                ]
            ])
            ->add('user_password', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Password must be at least 8 characters, include one uppercase letter and one number.',
                    ]),
                ]
            ])
            ->add('user_firstname', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\' -]+$/u',
                        'message' => 'First name can only contain letters, spaces, apostrophes, and hyphens.',
                    ]),
                ]
            ])
            ->add('user_lastname', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\' -]+$/u',
                        'message' => 'Last name can only contain letters, spaces, apostrophes, and hyphens.',
                    ]),
                ]
            ])
            ->add('user_birthday', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\LessThan([
                        'value' => (new \DateTime())->modify('-18 years'),
                        'message' => 'You must be at least 18 years old.',
                    ]),
                ],
            ])
            ->add('user_gender', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'placeholder' => 'Choose gender',
            ])
            ->add('user_picture', FileType::class, [
                'label' => 'Profile Picture (image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\Image([
                        'maxSize' => '5M',
                        'mimeTypesMessage' => 'Please upload a valid image file.',
                    ])
                ],
            ])
            ->add('user_phonenumber', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{8,15}$/',
                        'message' => 'Phone number should contain only digits and be 8 to 15 digits long.',
                    ])
                ]
            ])
            ->add('user_level', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Level must be a numeric value.',
                    ]),
                ]
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'role_name',
                'attr' => ['class' => 'form-control'],
                'label' => 'User Role',
                'placeholder' => 'Select a role'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

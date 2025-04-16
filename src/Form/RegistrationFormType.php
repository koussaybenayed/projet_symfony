<?php

namespace App\Form;
use App\Entity\Role;
use Symfony\Component\Validator\Constraints\Length;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_username')
            ->add('user_email', EmailType::class)
            ->add('user_password', PasswordType::class)
            ->add('user_firstname')
            ->add('user_lastname')
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
                'mapped' => false, // Don't automatically set it to the entity
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
                        'pattern' => '/^\d+$/',
                        'message' => 'Phone number should only contain digits.',
                    ])
                ]
            ])
            ->add('user_level')
            //->add('user_role')
            //->add('role', EntityType::class, [
                //'class' => Role::class,
               // 'choice_label' => 'role_id',
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}


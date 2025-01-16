<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'hash_property_path' => 'password',
                'constraints' => [
                    new UserPassword()
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' > 'off'
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length(
                        min: 5,
                        max: 120
                    )
                ],
                'first_options' => [
                    'label' => 'New Password',
                    'attr' => [
                        'autocomplete' => 'off' // Disable browser autocomplete
                    ]
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'autocomplete' => 'off' // Disable browser autocomplete
                    ]
                ],
                'mapped' => false, //they will not be automatically saved to the entity or handled by Doctrine
                'invalid_message' => 'The password fields must match.',
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            // ->add('roles')
            // ->add('password')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

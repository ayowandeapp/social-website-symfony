<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class DeleteAccountType extends AbstractType
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
            ->add('save', SubmitType::class, ['label' => 'Delete Account'])
            //             ->add('image', EntityType::class, [
//                 'class' => Image::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

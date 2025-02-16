<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'firstname',
            'attr' => [
                'placeholder' => 'register.firstname_placeholder'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label' => 'lastname',
            'attr' => [
                'placeholder' => 'register.lastname_placeholder'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'email',
            'attr' => [
                'placeholder' => 'auth.email_placeholder'
            ]
        ])
        ->add('password', PasswordType::class, [
            'label' => 'user.password_label',
            'attr' => [
                'autocomplete' => 'password', 
                'placeholder' => 'auth.password_placeholder'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

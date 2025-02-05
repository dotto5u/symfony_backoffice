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
        ->add('lastname', TextType::class, [
            'label' => 'register.lastname_label',
            'attr' => [
                'placeholder' => 'register.lastname_placeholder'
            ]
        ])
        ->add('firstname', TextType::class, [
            'label' => 'register.firstname_label',
            'attr' => [
                'placeholder' => 'register.firstname_placeholder'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'register.email_label',
            'attr' => [
                'placeholder' => 'register.email_placeholder'
            ]
        ])
        ->add('password', PasswordType::class, [
            'label' => 'register.password_label',
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'password', 
                'placeholder' => 'register.password_placeholder'
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

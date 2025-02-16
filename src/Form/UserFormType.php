<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'firstname',
                'attr' => [
                    'placeholder' => 'user.firstname_placeholder'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'lastname',
                'attr' => [
                    'placeholder' => 'user.lastname_placeholder'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'attr' => [
                    'placeholder' => 'user.email_placeholder'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.roles',
                'choices' => [
                    'user.role_user' => 'ROLE_USER',
                    'user.role_admin' => 'ROLE_ADMIN',
                    'user.role_manager' => 'ROLE_MANAGER',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $isEdit ? 'edit' : 'add',
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($roles) {
                    return is_array($roles) && count($roles) > 0 ? $roles[0] : null;
                },
                function ($role) {
                    return [$role];
                }
            ));

        if (!$isEdit) {
            $builder
                ->add('password', PasswordType::class, [
                    'label' => 'user.password_label',
                    'attr' => [
                        'placeholder' => 'user.password_placeholder'
                    ]
                ]);
        }        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false
        ]);
    }
}

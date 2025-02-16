<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'firstname',
                'attr' => [
                    'placeholder' => 'client.firstname_placeholder'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'lastname',
                'attr' => [
                    'placeholder' => 'client.lastname_placeholder'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'attr' => [
                    'placeholder' => 'client.email_placeholder'
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'client.phone_number_label',
                'attr' => [
                    'placeholder' => 'client.phone_number_placeholder'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'client.address_label',
                'attr' => [
                    'placeholder' => 'client.address_placeholder'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $isEdit ? 'edit' : 'add'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'is_edit' => false,
        ]);
    }
}

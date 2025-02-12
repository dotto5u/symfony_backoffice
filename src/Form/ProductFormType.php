<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'] ?? false;

        $builder
            ->add('name', TextType::class, [
                'label' => 'product.name_label',
                'attr' => [
                    'placeholder' => 'product.name_placeholder'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'product.description_label',
                'attr' => [
                    'placeholder' => 'product.description_placeholder'
                ]
            ])
            ->add('price', TextType::class, [
                'label' => 'product.price_label',
                'attr' => [
                    'placeholder' => 'product.price_placeholder'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $isEdit ? 'edit' : 'add',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'is_edit' => false,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Sales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('culture', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ex: Blé Tendre'
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: 76/220/11'
                ]
            ])
            ->add('column1_txt', TextType::class, [
                'attr' => [
                    'value' => 'BRS 07/09'
                ]
            ])
            ->add('column2_txt', TextType::class, [
                'attr' => [
                    'value' => 'BRS 10/12'
                ]
            ])

            ->add('l1_title')
            ->add('l1c1_value')
            ->add('l1c2_value')

            ->add('l2_title')
            ->add('l2c1_value')
            ->add('l2c2_value')

            ->add('l3_title')
            ->add('l3c1_value')
            ->add('l3c2_value')

            ->add('l4_title')
            ->add('l4c1_value')
            ->add('l4c2_value')

            ->add('l1_variation')
            ->add('l2_variation')
            ->add('l3_variation')
            ->add('l4_variation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms_sales',
            'data_class' => Sales::class,
        ]);
    }
}

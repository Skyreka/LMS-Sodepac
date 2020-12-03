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
            ->add('brs1_txt', TextType::class, [
                'attr' => [
                    'value' => 'BRS 07/09'
                ]
            ])
            ->add('brs1_deposit_value')
            ->add('brs1_crop_value')

            ->add('brs_crop_variation')
            ->add('brs_deposit_variation')

            ->add('brs2_txt', TextType::class, [
                'attr' => [
                    'value' => 'BRS 10/12'
                ]
            ])
            ->add('brs2_deposit_value')
            ->add('brs2_crop_value')
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

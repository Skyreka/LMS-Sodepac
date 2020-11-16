<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Warehouse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnicianCustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('email', EmailType::class)
            ->add('lastname', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('city', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('certification_phyto', TextType::class, [
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('warehouse', EntityType::class, [
                'required' => 'false',
                'class' => Warehouse::class,
                'choice_label' => function(Warehouse $warehouse) {
                    return $warehouse->getName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'translation_domain' => 'forms'
        ]);
    }
}

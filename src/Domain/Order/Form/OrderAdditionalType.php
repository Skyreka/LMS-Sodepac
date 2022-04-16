<?php

namespace App\Domain\Order\Form;

use App\Domain\Order\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAdditionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery', TextareaType::class, [
                'label' => 'Période et lieu de livraison :'
            ])
            ->add('conditions', TextareaType::class, [
                'label' => 'Conditions de paiement :'
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'translation_domain' => 'forms'
        ]);
    }
}

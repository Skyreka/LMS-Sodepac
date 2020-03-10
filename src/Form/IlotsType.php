<?php

namespace App\Form;

use App\Entity\Ilots;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IlotsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('size', null, [
                'help' => 'En hectare'
            ])
            ->add('type', null,[
                'label' => 'Type de sol'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ilots::class,
            'translation_domain' => 'forms'
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Ilots;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IlotsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('size', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => $options['max_size']
                ],
                'help' => 'En hectare | Espace restant : '. $options['max_size'] .' ha'
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
            'translation_domain' => 'forms',
            'max_size' => null
        ]);
    }
}

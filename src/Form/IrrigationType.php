<?php

namespace App\Form;

use App\Entity\Ilots;
use App\Entity\Irrigation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IrrigationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Pluviométrie' => 'Pluviometrie',
                    'Arrosage' => 'Arrosage'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'help' => 'En mm'
            ])
            ->add('comment')
            ->add('ilot', EntityType::class, [
                'class' => Ilots::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Irrigation::class,
            'translation_domain' => 'forms'
        ]);
    }
}

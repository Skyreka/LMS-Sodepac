<?php

namespace App\Form;

use App\Entity\Semis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemisInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la variété'
            ])
            ->add('quantity')
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'Quantité en pieds' => 'pieds',
                    'Quantité en kilos' => 'kilos'
                ]
            ])
            ->add('objective', null, [
                'label' => 'Objectif de rendement',
                'help' => 'En quintaux'
            ])
            ->add('comment')
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semis::class,
            'translation_domain' => 'forms'
        ]);
    }
}

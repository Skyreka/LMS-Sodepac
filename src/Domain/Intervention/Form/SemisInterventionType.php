<?php

namespace App\Domain\Intervention\Form;

use App\Domain\Intervention\Entity\Semis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemisInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la variété'
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité par Ha'
            ])
            ->add('unit', ChoiceType::class, [
                'choices' => [
                    'Quantité en pieds' => 'pieds',
                    'Quantité en kilos' => 'kilos'
                ]
            ])
            ->add('objective', NumberType::class, [
                'label' => 'Objectif de rendement',
                'help' => 'En quintaux'
            ])
            ->add('comment', TextareaType::class, [
                'required' => false
            ])
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'value' => date('d/m/Y'),
                    'readonly' => true
                ]
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Semis::class,
            'translation_domain' => 'forms'
        ]);
    }
}

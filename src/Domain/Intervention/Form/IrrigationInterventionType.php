<?php

namespace App\Domain\Intervention\Form;

use App\Entity\Irrigation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IrrigationInterventionType
 * @package App\Form
 */
class IrrigationInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité ',
                'help' => 'En mm'
            ])
            ->add('name', ChoiceType::class, [
                'choices' => [
                    'Arrosage' => 'Arrosage',
                    'Pluviométrie' => 'Pluviométrie'
                ]
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
            'data_class' => Irrigation::class,
            'translation_domain' => 'forms'
        ]);
    }
}

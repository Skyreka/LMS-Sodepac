<?php

namespace App\Domain\Intervention\Form;

use App\Domain\Intervention\Entity\Interventions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecolteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // User can update date if edit recolte from synthese but on normal intervention action view datepicker
        if($options['syntheseView'] == true) {
            $builder
                ->add('rendement', NumberType::class, [
                    'help' => 'Quintaux/hectare',
                    'required' => false
                ]);
        } else {
            $builder
                ->add('rendement', NumberType::class, [
                    'help' => 'Quintaux/hectare',
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
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interventions::class,
            'translation_domain' => 'forms',
            'syntheseView' => false
        ]);
    }
}

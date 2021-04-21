<?php

namespace App\Form;

use App\Entity\Interventions;
use App\Entity\Phyto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditInterventionQuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ( $options['data']->getType() == 'Fertilisant'
            OR $options['data']->getType() == 'Insecticide'
            OR $options['data']->getType() == 'Désherbant'
            OR $options['data']->getType() == 'Fongicide'
            OR $options['data']->getType() == 'Traitement-Divers') {
            $builder
                ->add('doseHectare');
        } else {
            $builder
                ->add('quantity');
        }
        $builder
            ->add('comment')
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'readonly' => true
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Interventions::class,
            'translation_domain' => 'forms'
        ]);
    }
}

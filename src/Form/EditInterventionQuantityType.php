<?php

namespace App\Form;

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
        $builder
            ->add('quantity')
            ->add('comment')
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'value' => date('Y-m-d')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phyto::class,
            'translation_domain' => 'forms'
        ]);
    }
}

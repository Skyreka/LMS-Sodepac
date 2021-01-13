<?php

namespace App\Form;

use App\Entity\Epandage;
use App\Entity\IndexEffluents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpandageInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('effluent', EntityType::class, [
                'class' => IndexEffluents::class,
                'choice_label' => 'name'
            ])
            ->add('quantity', NumberType::class, [
                'help' => 'En Tonne/Ha'
            ])
            ->add('comment')
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Epandage::class,
            'translation_domain' => 'forms'
        ]);
    }
}

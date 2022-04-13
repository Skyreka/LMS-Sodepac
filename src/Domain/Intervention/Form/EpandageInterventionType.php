<?php

namespace App\Domain\Intervention\Form;

use App\Domain\Index\Entity\IndexEffluents;
use App\Domain\Intervention\Entity\Epandage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('comment', TextareaType::class, [
                'required' => false
            ])
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'readonly' => true
                ]
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Epandage::class,
            'translation_domain' => 'forms'
        ]);
    }
}

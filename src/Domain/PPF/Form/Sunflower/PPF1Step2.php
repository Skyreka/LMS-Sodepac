<?php

namespace App\Domain\PPF\Form\Sunflower;

use App\Domain\PPF\Entity\PPF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF1Step2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intermediate_culture', ChoiceType::class, [
                'label' => 'Culture intermédiaire',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('push_back', ChoiceType::class, [
                'label' => 'Gestion des repousses',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('date_sow', DateType::class, [
                'label' => 'Date de semis'
            ])
            ->add('date_destruction', DateType::class, [
                'label' => 'Date de destruction'
            ])
            ->add('type_destruction', TextType::class, [
                'label' => 'Type de destruction',
                'required' => false
            ]);
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

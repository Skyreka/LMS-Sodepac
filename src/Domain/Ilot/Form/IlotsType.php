<?php

namespace App\Domain\Ilot\Form;

use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Entity\IndexGrounds;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IlotsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('number', NumberType::class, [
                'label' => 'Numéro PAC',
                'required' => false
            ])
            ->add('size', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => $options['max_size']
                ],
                'help' => 'En hectare | Espace restant : ' . $options['max_size'] . ' ha'
            ])
            ->add('type', EntityType::class, [
                'class' => IndexGrounds::class,
                'choice_label' => 'name',
                'required' => true
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ilots::class,
            'translation_domain' => 'forms',
            'max_size' => null
        ]);
    }
}
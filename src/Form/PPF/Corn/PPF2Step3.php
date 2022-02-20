<?php

namespace App\Form\PPF\Corn;

use App\Entity\PPF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF2Step3 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('need_plant', NumberType::class, [
                'label' => 'Besoin de la plante',
                'required' => false
            ])
            ->add('nitrogen_requirement', NumberType::class, [
                'label' => 'Besoin total d’azote de la parcelle',
                'required' => false
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

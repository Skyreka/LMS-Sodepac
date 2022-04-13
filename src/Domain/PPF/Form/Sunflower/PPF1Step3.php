<?php

namespace App\Domain\PPF\Form\Sunflower;

use App\Domain\PPF\Entity\PPF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF1Step3 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remainder_soil_sow', NumberType::class, [
                'label' => 'Mesure présents dans le sol au semis',
                'attr' => [
                    'placeholder' => 'En U/ha'
                ]
            ])
            ->add('qty_water_prev', NumberType::class, [
                'label' => 'Quantité d\'eau dirrigation prévue',
                'attr' => [
                    'placeholder' => 'En mm'
                ]
            ])
            ->add('resource_nitrate_content', NumberType::class, [
                'label' => 'Teneur en nitrate de la ressource',
                'attr' => [
                    'placeholder' => 'En mg/L'
                ]
            ]);
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

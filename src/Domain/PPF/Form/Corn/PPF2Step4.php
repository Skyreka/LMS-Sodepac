<?php

namespace App\Domain\PPF\Form\Corn;

use App\Domain\PPF\Entity\PPF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF2Step4 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remainder_soil_sow', NumberType::class, [
                'label' => 'Mesure présents dans le sol au semis'
            ])
            ->add('effect_meadow', NumberType::class, [
                'label' => 'Effet prairie',
            ])
            ->add('effect_residual_collected', NumberType::class, [
                'label' => 'Effet Résidu Récolte',
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

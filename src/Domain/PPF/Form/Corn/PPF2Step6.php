<?php

namespace App\Domain\PPF\Form\Corn;

use App\Domain\PPF\Entity\PPF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PPF2Step6
 * @package App\Form\PPF\Corn
 */
class PPF2Step6 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coefficient_multiple', NumberType::class, [
                'label' => 'Coefficient Multiplicateur',
                'attr' => [
                    'value' => '0.6'
                ]
            ])
            ->add('coefficient_use', NumberType::class, [
                'label' => 'Coefficient d’utilisation après le stade 4 feuilles'
            ])
            ->add('qty_azote_add', NumberType::class, [
                'label' => 'Azote apporté avant le stade 4 feuilles'
            ]);
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

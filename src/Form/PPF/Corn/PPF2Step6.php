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
                'mapped' => false
            ])
            ->add('coefficient_use', NumberType::class, [
                'label' => 'Coefficient d’utilisation après le stade 4 feuilles',
                'mapped' => false
            ])
            ->add('nutrigen_organic', NumberType::class, [
                'label' => 'Azote fourni par effluents organique',
                'mapped' => false
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

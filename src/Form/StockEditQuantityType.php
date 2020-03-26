<?php

namespace App\Form;

use App\Entity\Stocks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockEditQuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addQuantity', NumberType::class, [
                'mapped' => false,
                'label' => 'Quantité à ajouter',
                'help' => 'La valeur saisie sera additionné à la quantité de produit déjà enregistré.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stocks::class,
            'translation_domain' => 'forms'
        ]);
    }
}

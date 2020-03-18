<?php

namespace App\Form;

use App\Entity\Fumure;
use App\Entity\Products;
use App\Entity\Stocks;
use App\Repository\StocksRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FumureInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productInStock', EntityType::class, [
                'class' => Stocks::class,
                'label' => 'Produit en stock',
                'choice_label' => function(Stocks $stock) {
                    return $stock->getProduct()->getName().'   Disponible en stock: '. $stock->getQuantity().''.$stock->getUnit().'   Dose préconisée: '.$stock->getProduct()->getDose().''.$stock->getUnit().'/ha';
                },
                'query_builder' => function(StocksRepository $sr) use ( $options ) {
                    return $sr->findByExploitation( $options['user']->getExploitation() );
                },
                'mapped' => false
            ])
            ->add('quantity')
            ->add('reliquat')
            ->add('comment')
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fumure::class,
            'translation_domain' => 'forms',
            'user' => null
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Fumure;
use App\Entity\Stocks;
use App\Repository\StocksRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
                    return $stock->getProduct()->getName().'   Disponible en stock: '. $stock->getQuantity().''.$stock->getUnit( true ).'   Dose préconisée: '.$stock->getProduct()->getDose().''.$stock->getUnit( true ).'/ha';
                },
                'query_builder' => function(StocksRepository $sr) use ( $options ) {
                    return $sr->findProductStockFumureByExploitation( $options['user']->getExploitation() );
                },
                'mapped' => false,
                'placeholder' => 'Selectionner votre produit de fumure'
            ])
        ;

        $builder->get('productInStock')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ( $options ) {
                $form = $event->getForm();
                $this->addFields( $form->getParent(), $form->getData(), $options);
            }
        );
    }

    private function addFields(FormInterface $form, Stocks $stock, array $options)
    {
        $quantity = $stock->getProduct()->getDose() * $options['culture']->getSize();
        $form->add('quantity', NumberType::class, [
            'label' => 'Quantité : (Calculé : '.$quantity.''.$stock->getUnit(true).' )',
            'help' => 'Résultat de la dose préconisé : '. $stock->getProduct()->getDose().' * '.$options['culture']->getSize().' Taille de la culture en ha'
        ])
        ->add('reliquat')
        ->add('comment');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fumure::class,
            'translation_domain' => 'forms',
            'user' => null,
            'culture' => null
        ]);
    }
}

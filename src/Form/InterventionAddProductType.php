<?php

namespace App\Form;

use App\Entity\Doses;
use App\Entity\InterventionsProducts;
use App\Entity\Phyto;
use App\Entity\Stocks;
use App\Repository\DosesRepository;
use App\Repository\StocksRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionAddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productInStock', EntityType::class, [
                'class' => Stocks::class,
                'label' => 'Produit en stock',
                'choice_label' => function(Stocks $stock) {
                    return $stock->getProduct()->getName().'   Disponible en stock: '. $stock->getQuantity().''.$stock->getUnit( true );
                },
                'query_builder' => function(StocksRepository $sr) use ( $options ) {
                    return $sr->findProductInStockByExploitation( $options['user']->getExploitation() );
                },
                'mapped' => false,
                'placeholder' => 'Selectionner votre produit'
            ])
        ;

        //-- Event Listener on select product
        $builder->get('productInStock')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addDoseField( $form->getParent(), $form->getData(), $options);
            }
        );

        //-- Add default select for dose POST SET DATA
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ( $options ) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addDoseField( $form, null, null );
            }
        );
    }

    /**
     * Add dose select refresh with ajax
     * @param FormInterface $form
     * @param Stocks|null $stock
     */
    private function addDoseField(FormInterface $form, ?Stocks $stock, $options)
    {
        if (is_null($stock)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'doses',
                EntityType::class,
                null,
                [
                    'class' => Stocks::class,
                    'mapped' => false,
                    'choices' => [],
                    'required' => false,
                    'auto_initialize' => false,
                    'placeholder' => 'Selectionner un produit avant de choisir une dose'
                ]
            );
        } else {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('doses',
                EntityType::class,
                null,
                [
                    'class' => Doses::class,
                    'label' => 'Doses:',
                    'choice_label' => function(Doses $dose) {
                        return $dose->getApplication().' '.$dose->getDose().' '.$dose->getUnit();
                    },
                    'query_builder' => function(DosesRepository $dr) use ( $stock ) {
                        return $dr->findByProduct( $stock->getProduct() );
                    },
                    'auto_initialize' => false,
                    'mapped' => false,
                    'placeholder' => 'Selectionner votre produit'
                ]
            );
        }
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addQuantity($form->getParent(), $form->getData(), $options);
            }
        );
        $form->add($builder->getForm());
    }

    /**
     * Add select Quantity
     * @param FormInterface $form
     * @param Doses|null $dose
     */
    private function addQuantity(FormInterface $form, ?Doses $dose, $options)
    {

        if ($dose) {
            // Get Total Quantity
            $unitEnable = ['kg/ha', 'L/ha'];
            if (in_array($dose->getUnit(), $unitEnable)) {
                $totalQuantity = 'Valeur calculée: '. $dose->getDose() * $options['culture']->getSize();
                $resultMessage = 'Résultat de la dose préconisé : '.$dose->getDose().' * '.$options['culture']->getSize().'ha Taille de la culture en Ha';
            } else {
                $totalQuantity = '- Calcul non disponible avec cette unité';
                $resultMessage = 'Aucun calcul effectué';
            }
            $form
                ->add('quantity', NumberType::class, [
                    'label' => 'Quantité Totale '. $totalQuantity,
                    'help' => $resultMessage
                ])
                ->add('addNewProduct', CheckboxType::class, [
                    'mapped' => false,
                    'label' => 'Voulez-vous ajouter un nouveau produit ?',
                    'required' => false
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Ajouter'
                ])
            ;
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterventionsProducts::class,
            'user' => null,
            'culture' => null
        ]);
    }
}

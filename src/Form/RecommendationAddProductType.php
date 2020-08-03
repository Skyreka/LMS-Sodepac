<?php

namespace App\Form;

use App\Entity\Doses;
use App\Entity\Products;
use App\Entity\RecommendationProducts;
use App\Entity\Stocks;
use App\Repository\DosesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class RecommendationAddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'select2'
                ],
                'placeholder' => 'Sélectionner un produit'
            ])
        ;

        $builder->get('product')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addDoseField( $form->getParent(), $form->getData(), $options);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ( $options ) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addDoseField( $form, null, null );
            }
        );
    }

    private function addDoseField(FormInterface $form, ?Products $product, $options)
    {
        if (is_null($product)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'doses',
                EntityType::class,
                null,
                [
                    'class' => Stocks::class,
                    'label' => 'Dose uniquement à titre d\'information',
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
                    'choice_label' => function(Doses $dose) {
                        return $dose->getApplication().' '.$dose->getDose().' '.$dose->getUnit();
                    },
                    'query_builder' => function(DosesRepository $dr) use ( $product ) {
                        return $dr->findByProduct( $product->getId() );
                    },
                    'auto_initialize' => false,
                    'mapped' => false,
                    'placeholder' => 'Selectionner une dose avant de choisir une dose'
                ]
            );
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addOthersField( $form->getParent() );
            }
        );
        $form->add($builder->getForm());
    }

    private function addOthersField(FormInterface $form)
    {
        $form->add('quantity', null, [
                'label' => 'Quantité Totale'
            ])
            ->add('quantity_unit', ChoiceType::class, [
                'label' => 'Unité',
                'choices' => [
                    'Kilos' => 2,
                    'Litres' => 1
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecommendationProducts::class,
            'translation_domain' => 'forms'
        ]);
    }
}

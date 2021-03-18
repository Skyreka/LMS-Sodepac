<?php

namespace App\Form\PPF;

use App\Entity\PPF;
use App\Entity\PPFInput;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPFAddInput extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_added', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'by_reference' => true,
                'attr' => [
                    'class' => 'js-datepicker'
                ]
            ])
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'label' => 'Type d\'engrais',
                'choice_label' => function(Products $products) {
                    return $products->getName().' ( '.$products->getType().' ) | N:'.$products->getN().' P:'.$products->getP().' K:'.$products->getK() ;
                },
                'placeholder' => 'Selectionner votre produit',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité'
            ])
        ;

        //-- Event Listener on select product
        $builder->get('product')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->updateNPK( $form->getParent(), $form->getData(), $options);
            }
        );

        //-- Add default select for dose POST SET DATA
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ( $options ) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->updateNPK( $form, null, null );
            }
        );
    }

    /**
     * Update NPK
     * @param FormInterface $form
     * @param Products|null $products
     * @param $options
     */
    private function updateNPK(FormInterface $form, ?Products $products, $options)
    {
        if (is_null($products)) {
            $form
                ->add('n', NumberType::class, [
                    'label' => 'N:',
                    'required'=> false
                ])
                ->add('p', NumberType::class, [
                    'label' => 'P:',
                    'required'=> false
                ])
                ->add('k', NumberType::class, [
                    'label' => 'K:',
                    'required'=> false
                ])
            ;
        } else {
            $form
                ->add('n', NumberType::class, [
                    'label' => 'N:',
                    'required'=> false,
                    'attr' => [
                        'value' => $products->getN()
                    ]
                ])
                ->add('p', NumberType::class, [
                    'label' => 'P:',
                    'required'=> false,
                    'attr' => [
                        'value' => $products->getP()
                    ]
                ])
                ->add('k', NumberType::class, [
                    'label' => 'K:',
                    'required'=> false,
                    'attr' => [
                        'value' => $products->getK()
                    ]
                ])
            ;
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPFInput::class
        ]);
    }
}

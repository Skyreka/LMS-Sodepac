<?php

namespace App\Domain\Recommendation\Form;

use App\Domain\Doses\Entity\Doses;
use App\Domain\Doses\Repository\DosesRepository;
use App\Domain\Product\Entity\Products;
use App\Domain\Recommendation\Entity\RecommendationProducts;
use App\Domain\Stock\Entity\Stocks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ]);
        
        $builder->get('product')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addDoseField($form->getParent(), $form->getData(), $options);
            }
        );
        
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) use ($options) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addDoseField($form, null, null);
            }
        );
    }
    
    private function addDoseField(FormInterface $form, ?Products $product, $options)
    {
        if(is_null($product)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'dose',
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
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('dose',
                EntityType::class,
                null,
                [
                    'class' => Doses::class,
                    'choice_label' => function(Doses $dose) {
                        return $dose->getApplication() . ' ' . $dose->getDose() . ' ' . $dose->getUnit();
                    },
                    'query_builder' => function(DosesRepository $dr) use ($product) {
                        return $dr->findByProduct($product->getId());
                    },
                    'auto_initialize' => false,
                    'mapped' => false,
                    'placeholder' => 'Selectionner une dose avant de choisir une dose'
                ]
            );
        }
        
        $form->add($builder->getForm());
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecommendationProducts::class,
            'translation_domain' => 'forms'
        ]);
    }
}

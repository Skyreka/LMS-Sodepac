<?php

namespace App\Form;

use App\Entity\Products;
use App\Entity\ProductsCategory;
use App\Entity\Stocks;
use App\Repository\ProductsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockAddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => ProductsCategory::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Sélectionnez une catégorie de produit',
                'label' => 'Catégorie de produit'
            ])
        ;

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addProductField( $form->getParent(), $form->getData());
            }
        );
    }

    /**
     * Add product field to form
     * @param FormInterface $form
     * @param ProductsCategory $productsCategory
     */
    private function addProductField(FormInterface $form, ProductsCategory $productsCategory)
    {
        // Add new field
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'product',
            EntityType::class,
            null,
            [
                'class' => Products::class,
                'label' => 'Produits ('.$productsCategory->getName().')',
                'auto_initialize' => false,
                'choice_label' => 'name',
                'query_builder' => function (ProductsRepository $pr) use ($productsCategory) {
                    return $pr->findProductsByCategory( $productsCategory );
                }
            ]
        );
        // Event Listening
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addQuantityField( $form->getParent() );
            }
        );
        $form->add( $builder->getForm() );
    }

    /**
     * Add quantity & unit to field
     * @param FormInterface $form
     */
    private function addQuantityField(FormInterface $form )
    {
        $form->add('quantity');
        $form->add('unit', ChoiceType::class, [
            'choices' => $this->getChoices()
        ]);
    }

    private function getChoices()
    {
        $choices = Stocks::UNIT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stocks::class,
            'translation_domain' => 'forms'
        ]);
    }
}

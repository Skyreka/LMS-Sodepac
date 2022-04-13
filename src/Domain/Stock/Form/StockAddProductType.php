<?php

namespace App\Domain\Stock\Form;

use App\Domain\Product\Entity\Products;
use App\Domain\Product\Repository\ProductsRepository;
use App\Domain\Stock\Entity\Stocks;
use App\Domain\Intervention\Entity\ProductsCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockAddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'query_builder' => function(ProductsRepository $pr) use ($options) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC')
                        ->andWhere('p.private = 0')
                        ->andWhere('p.parent_product is NULL')
                        ->andWhere('p.isActive = 1');
                },
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un produit',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('quantity', NumberType::class)
            ->add('unit', ChoiceType::class, [
                'choices' => $this->getChoices()
            ]);
    }
    
    
    private function getChoices()
    {
        $choices = Stocks::UNIT;
        $output  = [];
        foreach($choices as $k => $v) {
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

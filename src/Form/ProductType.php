<?php

namespace App\Form;

use App\Entity\ProductCategory;
use App\Entity\Products;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ( $options['edit_name'] ) {
            $builder->add('name', TextType::class);
        }

        $builder
            ->add('category', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => function( ProductCategory $category ) {
                    return $category->getName();
                },
                'query_builder' => function( ProductCategoryRepository $pcr ) {
                    return $pcr->createQueryBuilder( 'i' )
                        ->where('i.id != 1');
                }
            ])
            ->add('rpd', NumberType::class, [
                'required' => false
            ])
            ->add('price', NumberType::class)
            ->add('parent_product', EntityType::class, [
                'class' => Products::class,
                'required' => false,
                'choice_label' => function( Products $products ) {
                    return $products->getName();
                },
                'query_builder' => function( ProductsRepository $pr ) {
                    return $pr->createQueryBuilder( 'p' )
                        ->where('p.id_lex is not null')
                        ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'translation_domain' => 'forms',
            'edit_name' => false
        ]);
    }
}

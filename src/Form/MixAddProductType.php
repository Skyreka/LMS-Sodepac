<?php

namespace App\Form;

use App\Entity\MixProducts;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MixAddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'query_builder' => function (ProductsRepository $pr) use ( $options ) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC')
                        ->andWhere('p.private = 0');
                },
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un produit',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MixProducts::class,
            'translation_domain' => 'forms'
        ]);
    }
}

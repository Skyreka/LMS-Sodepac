<?php

namespace App\Domain\Catalogue\Form;

use App\Domain\Auth\Users;
use App\Domain\Catalogue\Entity\CanevasDisease;
use App\Domain\Catalogue\Entity\CanevasIndex;
use App\Domain\Catalogue\Entity\CanevasProduct;
use App\Domain\Catalogue\Entity\CanevasStep;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Repository\CanevasIndexRepository;
use App\Domain\Product\Entity\Products;
use App\Domain\Product\Repository\ProductsRepository;
use Doctrine\DBAL\Types\FloatType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CanevasProductEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', Select2EntityType::class, [
                'remote_route' => 'admin_canevas_select_product_ajax',
                'class' => Products::class,
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000,
                'language' => 'fr',
                'placeholder' => 'Choisir un produit'
            ])
            ->add('dose', NumberType::class, [
                'required' => true
            ])
            ->add('unit', TextType::class, [
                'required' => true
            ])
            ->add('color', TextType::class, [
                'required' => true
            ])

            ->add('step', HiddenType::class, [
                'mapped' => false
            ])
            ->add('disease', HiddenType::class, [
                'mapped' => false
            ])
            ->add('btn_id', HiddenType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => null
        ]);
    }
}

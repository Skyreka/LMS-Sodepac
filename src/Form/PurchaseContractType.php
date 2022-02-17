<?php

namespace App\Form;

use App\Entity\PurchaseContract;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class PurchaseContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cultureType', ChoiceType::class, [
                'label' => 'Type de culture',
                'choices' => $this->getCultureType()
            ])
            ->add('customer', Select2EntityType::class, [
                'remote_route' => 'order_select_data',
                'class' => Users::class,
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'fr',
                'placeholder' => 'Choisir un client'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PurchaseContract::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getCultureType()
    {
        $choices = PurchaseContract::CULTURETYPE;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}

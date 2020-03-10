<?php

namespace App\Form;

use App\Entity\Bsv;
use App\Entity\Users;
use Doctrine\ORM\Mapping\Entity;
use http\Client\Curl\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsvSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('indexCultures', EntityType::class, [
                'class' => 'indexCultures',
                'placeholder' => 'Sélectionnez le type de culture',
                'mapped' => false,
                'required' => false
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bsv::class,
            'translation_domain' => 'forms'
        ]);
    }
}

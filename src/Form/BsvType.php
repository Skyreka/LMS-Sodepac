<?php

namespace App\Form;

use App\Entity\Bsv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('first_file')
            ->add('second_file')
            ->add('sent')
            ->add('creation_date')
            ->add('send_date')
            ->add('technician')
            ->add('customers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bsv::class,
        ]);
    }
}

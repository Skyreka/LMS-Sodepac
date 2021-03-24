<?php

namespace App\Form\PPF;

use App\Entity\IndexEffluents;
use App\Entity\PPF;
use App\Repository\IndexEffluentsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPFStep4 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('effluent', EntityType::class, [
                'class' => IndexEffluents::class,
                'label' => 'Effluent',
                'choice_label' => function(IndexEffluents $indexEffluents) {
                    return $indexEffluents->getName() . ' - Teneur en azote : ' . $indexEffluents->getNitrogenContent();
                }
            ])
            ->add('qty_ependu', TextType::class, [
                'label' => 'Quantité epandue ( Tou m3 / Ha )'
            ])
            ->add('date_spreading', DateType::class, [
                'label' => 'Date d\'épandage'
            ])
            ->add('coefficient_equivalence', TextType::class, [
                'label' => 'Coefficient équivalence'
            ])
            ->add('qty_azote_add', NumberType::class, [
                'label' => 'Quantité d\'azote total à apporter'
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

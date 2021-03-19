<?php

namespace App\Form\PPF;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Entity\PPF;
use App\Entity\Recommendations;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPFStep2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intermediate_culture', ChoiceType::class, [
                'label' => 'Culture intermédiaire',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('push_back', ChoiceType::class, [
                'label' => 'Gestion des repousses',
                'choices' => [
                    'Non' => 0,
                    'Oui' => 1
                ]
            ])
            ->add('date_sow', DateType::class, [
                'label' => 'Date de semis'
            ])
            ->add('date_destruction', DateType::class, [
                'label' => 'Date de destruction'
            ])
            ->add('type_destruction', TextType::class, [
                'label' => 'Type de destruction'
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

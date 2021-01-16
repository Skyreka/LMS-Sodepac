<?php

namespace App\Form;

use App\Entity\Ilots;
use App\Entity\Irrigation;
use App\Repository\IlotsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IrrigationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ilot', EntityType::class, [
                'class' => Ilots::class,
                'query_builder' => function (IlotsRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.exploitation = :exp')
                        ->setParameter('exp', $options['exp']->getExploitation());
                },
                'choice_label' => 'name'
            ])
            ->add('quantity', NumberType::class, [
                'help' => 'En mm'
            ])
            ->add('intervention_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'value' => date('d/m/Y'),
                    'readonly' => true
                ]
            ])
            ->add('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Irrigation::class,
            'translation_domain' => 'forms',
            'exp' => null
        ]);
    }
}

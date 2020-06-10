<?php

namespace App\Form;

use App\Entity\Analyse;
use App\Entity\Ilots;
use App\Repository\IlotsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalyseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('measure', NumberType::class, [
                'help' => "Reliquat d'Azote Observé en Unité"
            ])
            ->add('ilot', EntityType::class, [
                'class' => Ilots::class,
                'query_builder' => function (IlotsRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.exploitation = :exp')
                        ->setParameter('exp', $options['exp']->getExploitation());
                },
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Analyse::class,
            'translation_domain' => 'forms',
            'exp' => null
        ]);
    }
}

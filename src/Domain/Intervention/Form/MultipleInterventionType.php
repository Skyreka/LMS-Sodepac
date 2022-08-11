<?php

namespace App\Domain\Intervention\Form;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Repository\IndexCulturesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultipleInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selectCulture', EntityType::class, [
                'class' => IndexCultures::class,
                'mapped' => false,
                'label' => ' ',
                'choice_label' => function(IndexCultures $indexCultures) {
                    return $indexCultures->getName();
                },
                'query_builder' => function(IndexCulturesRepository $icr) use ($options) {
                    return $icr->findActiveCultureByExploitation($options['user']->getExploitation(), true);
                },
                'placeholder' => 'Sélectionner votre culture',
                'attr' => [
                    'onchange' => 'this.form.submit()'
                ]
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IndexCultures::class,
            'user' => null
        ]);
    }
}

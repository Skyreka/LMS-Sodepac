<?php

namespace App\Form;

use App\Entity\Recommendations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationMentionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mention', ChoiceType::class, [
                'label' => 'Méthode alternative',
                'choices' => [
                    'Ne pas choisir de méthode' => null,
                    '2017-004 Lutter contre les chenilles foreuses de fruit en vergers au moyen du virus de la granulose' => '2017-004',
                    '2017-005' => '2017-005',
                    '2017-006' => '2017-006',
                    '2017-008' => '2017-008',
                    '2017-023' => '2017-023',
                    '2017-026' => '2017-026',
                    '2017-028' => '2017-028',
                    '2018-034' => '2018-034',
                    '2018-037' => '2018-037',
                    '2018-039' => '2018-039',
                    '2018-040' => '2018-040',
                    '2018-041' => '2018-041',
                    '2018-044' => '2018-044',
                    '2019-018' => '2019-018',
                    '2020-007' => '2020-007',
                    '2020-009' => '2020-009',
                    '2020-038' => '2020-038',
                ]
            ])
            ->add('mention_txt', TextareaType::class, [
                'label' => 'Champs Libre',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => Recommendations::class,
        ]);
    }
}

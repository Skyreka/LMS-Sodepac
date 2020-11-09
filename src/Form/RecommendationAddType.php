<?php

namespace App\Form;

use App\Entity\Exploitation;
use App\Entity\IndexCultures;
use App\Entity\Recommendations;
use App\Entity\Users;
use App\Repository\IndexCulturesRepository;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class RecommendationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('exploitation', Select2EntityType::class, [
                'remote_route' => 'recommendations_select_data',
                'class' => Exploitation::class,
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'fr',
                'placeholder' => 'Choisir un utilisateur',
                'help' => 'Utilisateur ayant une exploitation active uniquement visible.'
            ])
            ->add('culture', EntityType::class, array(
                'class' => IndexCultures::class,
                'choice_label' => function (IndexCultures $culture) {
                    return $culture->getName();
                },
                'query_builder' => function (IndexCulturesRepository $icr) {
                    return $icr->findCulturesCanevasAvailable( true );
                }
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recommendations::class,
            'user' => null
        ]);
    }
}

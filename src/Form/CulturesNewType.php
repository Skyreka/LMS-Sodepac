<?php

namespace App\Form;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use App\Entity\IndexEffluents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CulturesNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('precedent', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'label' => 'Culture Précédent'
            ])
            ->add('name', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('comments', TextType::class, [
                'label' => 'Commentaire nom de culture'
            ])
            ->add('size', NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => $options['max_size']
                ],
                'label' => 'Taille de la culture',
                'help' => 'En hectare | Espace restant : '. $options['max_size'] .' ha'
            ])
            ->add('bio', null, [
                'label' => 'Culture biodégradable ?'
            ])
            ->add('production', null, [
                'label' => 'Culture en production ?'
            ])
            ->add('residue', null, [
                'label' => 'Avez-vous laissé le résidu ?'
            ])
            ->add('znt', NumberType::class, [
                'attr' => [
                    'value' => 1
                ]
            ])
            ->add('effluent', EntityType::class, [
                'class' => IndexEffluents::class,
                'label' => 'Apport d\'effluents',
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cultures::class,
            'translation_domain' => 'forms',
            'max_size' => null
        ]);
    }
}

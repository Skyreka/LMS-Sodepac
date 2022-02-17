<?php

namespace App\Form;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use App\Entity\IndexEffluents;
use App\Repository\IndexCulturesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'attr' => [
                    'class' => 'select2'
                ],
                'choice_label' => 'name',
                'placeholder' => 'Aucune',
                'required' => false,
                'label' => 'Culture Précédente',
                'query_builder' => function(IndexCulturesRepository $icr) {
                    return $icr->findDisplay();
                }
            ])
            ->add('name', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'select2'
                ],
                'label' => 'Choix de la culture',
                'label_attr' => array(
                    'class' => 'font-weight-bold'
                ),
                'query_builder' => function(IndexCulturesRepository $icr) {
                    return $icr->findDisplay();
                },
                'required' => false
            ])
            ->add('comments', TextType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('size',  NumberType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => $options['max_size']
                ],
                'label' => 'Surface de la culture',
                'help' => 'En hectare | Espace restant : '. $options['max_size'] .' ha',
                'required' => false
            ])
            ->add('bio', CheckboxType::class, [
                'label' => 'Culture bio ?',
                'required' => false
            ])
            ->add('permanent', CheckboxType::class, [
                'label' => 'Culture permanente ?',
                'required' => false
            ])
            ->add('production', CheckboxType::class, [
                'label' => 'Culture en production ?',
                'attr' => ['checked' => 'checked'],
                'required' => false
            ])
            ->add('residue', CheckboxType::class, [
                'label' => 'Avez-vous laissé le résidu ?',
                'required' => false
            ])
            ->add('znt', HiddenType::class, [
                'attr' => [
                    'value' => 1
                ],
                'required' => false
            ])
            ->add('effluent', EntityType::class, [
                'class' => IndexEffluents::class,
                'label' => 'Apport d\'effluents',
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
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

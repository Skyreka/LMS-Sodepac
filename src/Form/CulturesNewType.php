<?php

namespace App\Form;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use App\Entity\IndexEffluents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CulturesNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bio')
            ->add('precedent', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'label' => 'Culture Précédent'
            ])
            ->add('name', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name'
            ])
            ->add('production')
            ->add('comments', null, [
                'label' => 'Commentaire du nom de culture'
            ])
            ->add('size', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => $options['max_size']
                ],
                'label' => 'Taille de la culture',
                'help' => 'En hectare | Espace restant : '. $options['max_size'] .' ha'
            ])
            ->add('residue', null, [
                'help' => 'Avez-vous laissé le résidu ?'
            ])
            ->add('znt')
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

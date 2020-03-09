<?php

namespace App\Form;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('comments', null, [
                'label' => 'Commentaire de nom de culture'
            ])
            ->add('size', null, [
                'label' => 'Taille de la culture'
            ])
            ->add('residue')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cultures::class,
            'translation_domain' => 'forms'
        ]);
    }
}

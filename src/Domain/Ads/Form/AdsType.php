<?php

namespace App\Domain\Ads\Form;

use App\Domain\Ads\Entity\Ads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => '30 caractères max',
                'attr' => [
                    'maxlength' => 30
                ]
            ])
            ->add('description', TextType::class, [
                'help' => '120 caractères max',
                'attr' => [
                    'maxlength' => 120
                ]
            ])
            ->add('imageFile', FileType::class, [
                'help' => 'Uniquement image d\'illustration, Pas de texte, Hauteur maximum 150px'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ads::class,
            'translation_domain' => 'forms'
        ]);
    }
}

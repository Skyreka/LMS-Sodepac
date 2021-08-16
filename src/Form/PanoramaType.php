<?php

namespace App\Form;

use App\Entity\Panoramas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PanoramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('first_file', FileType::class, [
                'label' => 'PDF',
                'help' => 'Max 5Mo',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Le format du fichier ne correspond pas à celui demandé',
                    ])
                ],
            ])
            ->add('second_file', FileType::class, [
                'label' => 'Première image',
                'help' => 'Facultatif, Max 5Mo, PNG/JPG uniquement',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M'
                    ])
                ],
            ])
            ->add('third_file', FileType::class, [
                'label' => 'Seconde image',
                'help' => 'Facultatif, Max 5Mo, PNG/JPG uniquement',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Panoramas::class,
            'translation_domain' => 'forms'
        ]);
    }
}

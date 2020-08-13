<?php

namespace App\Form;

use App\Entity\Panoramas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PanoramaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('first_file', FileType::class, [
                'label' => 'PDF',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Le format du fichier ne correspond pas à celui demandé',
                    ])
                ],
            ])
            ->add('second_file', FileType::class, [
                'label' => 'Image 1',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4000k'
                    ])
                ],
            ])
            ->add('third_file', FileType::class, [
                'label' => 'Image 2',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'dropify-fr'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '4000k'
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

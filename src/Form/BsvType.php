<?php

namespace App\Form;

use App\Entity\Bsv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BsvType extends AbstractType
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
                        'maxSize' => '5000k',
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
                        'maxSize' => '1024k'
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
                        'maxSize' => '1024k'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bsv::class,
            'translation_domain' => 'forms'
        ]);
    }
}

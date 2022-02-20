<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignatureSign extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('code', TextType::class, [
                'label' => 'Code OTP',
                'help' => 'Code reçu par e-mail à l\'instant'
            ])

            ->add('terms1', CheckboxType::class, [
                'label' => 'Je certifie être détenteur du certiphyto utilisateur professionnel en cours de validité et atteste être pleinement responsable du choix des spécialités présentées dans ce catalogue.'
            ])
            ->add('terms2', CheckboxType::class, [
                'label' => 'Je valide et accepte ce bon de commande.'
            ])
            ->add('terms3', CheckboxType::class, [
                'label' => 'En signant ce document, j\'atteste avoir reçu, lors de la vente, l’ensemble des informations appropriées sur l’emploi des produits.'
            ])
            ->add('terms4', CheckboxType::class, [
                'label' => 'Je reconnais avoir pris connaissance des CGV'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Signer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'forms'
        ]);
    }
}

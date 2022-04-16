<?php

namespace App\Domain\Order\Form;

use App\Domain\Order\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderSignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('term1', CheckboxType::class, [
                'label' => 'Je certifie être détenteur du certiphyto utilisateur professionnel en cours de validité et atteste être pleinement responsable du choix des spécialités présentées dans ce catalogue.',
                'mapped' => false,
                'required' => true
            ])
            ->add('term2', CheckboxType::class, [
                'label' => 'Je soussigné ' . $options['user']->getIdentity() . ', valide et accepte ce bon de commande',
                'mapped' => false,
                'required' => true
            ])
            ->add('term3', CheckboxType::class, [
                'label' => 'En signant ce document, ' . $options['user']->getIdentity() . ' atteste avoir reçu, lors de la vente, l’ensemble des informations appropriées sur l’emploi des produits.',
                'mapped' => false,
                'required' => true
            ])
            ->add('term4', CheckboxType::class, [
                'label' => 'Je reconnais avoir pris connaissance des CGV.',
                'mapped' => false,
                'required' => true
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'user' => null,
            'translation_domain' => 'forms'
        ]);
    }
}

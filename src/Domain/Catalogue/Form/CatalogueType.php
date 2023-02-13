<?php

namespace App\Domain\Catalogue\Form;

use App\Domain\Auth\Users;
use App\Domain\Catalogue\Entity\CanevasIndex;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Repository\CanevasIndexRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CatalogueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', Select2EntityType::class, [
                'remote_route' => 'catalogue_select_user_ajax',
                'class' => Users::class,
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000,
                'language' => 'fr',
                'placeholder' => 'Choisir un utilisateur',
                'help' => 'Utilisateur ayant une exploitation active uniquement visible.'
            ])
            ->add('canevas', EntityType::class, [
                'class' => CanevasIndex::class,
                'label' => 'Liste des Canevas',
                'choice_label' => function(CanevasIndex $canevas) {
                    return $canevas->getName();
                },
                'query_builder' => function(CanevasIndexRepository $icr) {
                    return $icr->findAllCanevas();
                }
            ])
            ->add('cultureSize', NumberType::class, [
                'label' => 'Superficie de la culture (Ha)',
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Catalogue::class
        ]);
    }
}

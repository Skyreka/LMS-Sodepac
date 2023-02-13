<?php

namespace App\Domain\Catalogue\Form;

use App\Domain\Auth\Users;
use App\Domain\Catalogue\Entity\CanevasDisease;
use App\Domain\Catalogue\Entity\CanevasIndex;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Repository\CanevasIndexRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CanevasDiseaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('sort', IntegerType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => CanevasDisease::class
        ]);
    }
}

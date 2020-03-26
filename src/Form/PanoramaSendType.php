<?php

namespace App\Form;

use App\Entity\Panoramas;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanoramaSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
             ->add('customers', EntityType::class, [
                 'class' => Users::class,
                 'expanded'     => true,
                 'multiple'     => true,
                 'query_builder' => function (UsersRepository $er) use ( $options ) {
                     return $er->createQueryBuilder('u')
                         ->orderBy('u.status', 'ASC')
                         ->andWhere('u.technician = :technician')
                         ->setParameter('technician', $options['user']->getId() );
                 },
                 'choice_label' => function(Users $user) {
                     return $user->getFirstname() . ' ' . $user->getLastname();
                 }
             ])
         ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Panoramas::class,
            'translation_domain' => 'forms',
            'user' => null
        ]);
    }
}

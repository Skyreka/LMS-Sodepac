<?php

namespace App\Form;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('city')
            ->add('status', ChoiceType::class, [
                'choices' => $this->getStatus()
            ])
            ->add('pack', ChoiceType::class, [
                'choices' => $this->getPack()
            ])
            ->add('certification_phyto')
            ->add('technician', EntityType::class, [
                'class' => Users::class,
                'expanded'     => false,
                'multiple'     => false,
                'query_builder' => function (UsersRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.status', 'ASC')
                        ->andWhere('u.status = :status')
                        ->setParameter('status', 'ROLE_TECHNICIAN');
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
            'data_class' => Users::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getStatus()
    {
        $choices = Users::STATUS;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }

    private function getPack()
    {
        $choices = Users::PACK;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
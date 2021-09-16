<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Warehouse;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('lastname', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('company', TextType::class, [
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'off'
                ],
                'help' => 'Adresse fonctionnel uniquement'
            ])
            ->add('phone', TextType::class, [
                'empty_data' => '',
                'help' => 'Format: 06 00 00 00 00',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('address', TextType::class)
            ->add('postal_code', TextType::class)
            ->add('city', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('pack', ChoiceType::class, [
                'choices' => $this->getPack(),
                'disabled' => $options['is_edit']
            ])
            ->add('warehouse', EntityType::class, [
                'required' => 'false',
                'class' => Warehouse::class,
                'disabled' => $options['is_edit'],
                'choice_label' => function(Warehouse $warehouse) {
                    return $warehouse->getName();
                },
            ])
            ->add('certification_phyto', TextType::class, [
                'required' => false,
            ])
            ->add('technician', EntityType::class, [
                'class' => Users::class,
                'expanded'     => false,
                'multiple'     => false,
                'disabled' => $options['is_edit'] or $options['is_technician'],
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
            'translation_domain' => 'forms',
            'is_edit' => false,
            'is_technician' => false
        ]);
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

<?php

namespace App\Form;

use App\Entity\BsvUsers;
use App\Entity\Cultures;
use App\Entity\Exploitation;
use App\Entity\Ilots;
use App\Entity\IndexCultures;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsvSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cultures', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sélectionnez une culture'
            ])
        ;

        $builder->get( 'cultures')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addUserField( $form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addUserField( $form, null );
            }
        );
    }

    private function addUserField(FormInterface $form, ?IndexCultures $indexCultures)
    {
        if (is_null($indexCultures)) {
            $form->add('user', EntityType::class, [
                'class' => Users::class,
                'mapped' => false,
                'choices' => [],
                'required' => false,
                'placeholder' => 'Selectionner une culture avant de choisir un utilisateur'
            ]);
        } else {
            $form->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => function(Users $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'query_builder' => function (UsersRepository $er) use ( $indexCultures ) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin( Exploitation::class, 'e', 'WITH', 'u.id = e.users')
                        ->leftJoin(Ilots::class, 'i', 'WITH', 'e.id = i.exploitation')
                        ->leftJoin(Cultures::class, 'c', 'WITH', 'i.id = c.ilot')
                        ->leftJoin(IndexCultures::class, 'ic', 'WITH','c.name = ic.id')
                        ->andWhere('ic.id = :indexC')
                        ->setParameter('indexC', $indexCultures->getId());
                },
                'mapped' => false,
                'expanded' => true,
                'multiple' => true
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BsvUsers::class,
            'translation_domain' => 'forms'
        ]);
    }
}

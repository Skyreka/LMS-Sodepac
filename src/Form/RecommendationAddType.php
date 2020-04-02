<?php

namespace App\Form;

use App\Entity\Exploitation;
use App\Entity\IndexCultures;
use App\Entity\Recommendations;
use App\Entity\Users;
use App\Repository\IndexCulturesRepository;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('exploitation', EntityType::class, [
                'mapped' => false,
                'class' => Users::class,
                'label' => 'Sélection du client',
                'choice_label' => function(Users $user) {
                    return $user->getIdentity().' ('.$user->getExploitation()->getSize().'ha)';
                },
                'query_builder' => function(UsersRepository $usersRepository) {
                    return $usersRepository->createQueryBuilder('u')
                        ->leftJoin(Exploitation::class, 'e', 'WITH', 'e.users = u.id')
                        ->where('u.status = :status')
                        ->setParameter('status', 'ROLE_USER');
                },
                'placeholder' => 'Selectionner un utilisateur'
            ])
        ;

        $builder->get('exploitation')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addCultureField( $form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addCultureField( $form, null );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recommendations::class,
        ]);
    }

    /**
     * Add Culture Listing of Exploitation of other precedent selected
     * @param FormInterface $form
     * @param Users $user
     */
    private function addCultureField(FormInterface $form, ?Users $user)
    {
        if (is_null($user)) {
            $form->add('culture', EntityType::class, [
                'class' => IndexCultures::class,
                'choices' => [],
                'required' => false,
                'placeholder' => 'Selectionner un utilisateur avant de choisir une culture'
            ]);
        } else {
            $form->add('culture', EntityType::class, array(
                'class' => IndexCultures::class,
                'choice_label' => function (IndexCultures $culture) {
                    return $culture->getName();
                },
                'query_builder' => function (IndexCulturesRepository $cr) use ($user) {
                    return $cr->findCulturesByExploitation( $user->getExploitation(), true );
                }
            ));
        }
    }
}

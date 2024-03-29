<?php

namespace App\Domain\Bsv\Form;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Auth\Users;
use App\Domain\Bsv\Entity\BsvUsers;
use App\Domain\Culture\Entity\Cultures;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Repository\IndexCulturesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlashSendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cultures', EntityType::class, [
                'class' => IndexCultures::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sélectionner une culture',
                'attr' => [
                    'class' => 'select2'
                ],
                'query_builder' => function(IndexCulturesRepository $icr) {
                    return $icr->findDisplay();
                }
            ])
            ->add('display_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'mapped' => false,
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'readonly' => true
                ],
                'label' => 'Date d\'envoi',
                'help' => 'Remplir uniquement en cas d\'envoi différé.'
            ]);
        
        $builder->get('cultures')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addUserField($form->getParent(), $form->getData());
            }
        );
        
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addUserField($form, null);
            }
        );
    }
    
    /**
     * @param FormInterface $form
     * @param IndexCultures|null $indexCultures
     */
    private function addUserField(FormInterface $form, ?IndexCultures $indexCultures)
    {
        if(is_null($indexCultures)) {
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
                    return $user->getIdentity();
                },
                'query_builder' => function(UsersRepository $er) use ($indexCultures) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin(Exploitation::class, 'e', 'WITH', 'u.id = e.users')
                        ->leftJoin(Ilots::class, 'i', 'WITH', 'e.id = i.exploitation')
                        ->leftJoin(Cultures::class, 'c', 'WITH', 'i.id = c.ilot')
                        ->leftJoin(IndexCultures::class, 'ic', 'WITH', 'c.name = ic.id')
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

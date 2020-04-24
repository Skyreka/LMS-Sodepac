<?php

namespace App\Form;

use App\Entity\Ilots;
use App\Entity\IndexCultures;
use App\Repository\IlotsRepository;
use App\Repository\IndexCulturesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultipleInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selectCulture', EntityType::class, [
                'class' => IndexCultures::class,
                'mapped' => false,
                'label' => 'Cultures',
                'choice_label' => function( IndexCultures $indexCultures ) {
                    return $indexCultures->getName();
                },
                'query_builder' => function( IndexCulturesRepository $icr) use ( $options ) {
                    return $icr->findCulturesByExploitation( $options['user']->getExploitation(), true );
                },
                'placeholder' => 'Sélectionner votre culture'
            ])
        ;

        $builder->get('selectCulture')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addIlotsField( $form->getParent(), $form->getData(), $options);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ( $options ) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addIlotsField( $form, null, null );
            }
        );
    }

    private function addIlotsField(FormInterface $form, ?IndexCultures $indexCultures, $options)
    {
        if (is_null($indexCultures)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'ilots',
                HiddenType::class,
                null,
                [
                    'auto_initialize' => false,
                    'mapped' => false
                ]
            );
        } else {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('ilots',
                EntityType::class,
                null,
                [
                    'class' => Ilots::class,
                    'label' => 'Ilots incluant votre culture:',
                    'choice_label' => function (Ilots $ilot) {
                        return $ilot->getName();
                    },
                    'query_builder' => function (IlotsRepository $ir) use ($indexCultures, $options) {
                        return $ir->findByIndexCulture($indexCultures->getId(), $options['user']->getExploitation(), true);
                    },
                    'auto_initialize' => false,
                    'mapped' => false,
                    'placeholder' => 'Choisir ilot',
                    'expanded' => true,
                    'multiple' => true
                ]
            );
        }

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IndexCultures::class,
            'user' => null
        ]);
    }
}

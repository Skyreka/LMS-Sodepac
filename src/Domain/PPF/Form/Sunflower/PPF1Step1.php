<?php

namespace App\Domain\PPF\Form\Sunflower;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Ilot\Repository\IlotsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF1Step1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ilot', EntityType::class, [
                'class' => Ilots::class,
                'label' => 'Ilot de l\'utilisateur',
                'choice_label' => function(Ilots $ilot) {
                    return $ilot->getName() . ' ( ' . $ilot->getSize() . ' ha )';
                },
                'query_builder' => function(IlotsRepository $ir) use ($options) {
                    return $ir->findIlotsFromExploitation($options['exploitation'], true);
                },
                'mapped' => false,
                'placeholder' => 'Selectionner un ilot'
            ]);

        //-- Event Listener on select product
        $builder->get('ilot')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->updateCultureField($form->getParent(), $form->getData(), $options);
            }
        );

        //-- Add default select for dose POST SET DATA
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) use ($options) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->updateCultureField($form, null, null);
            }
        );
    }

    /**
     * Update culture by ilot
     * @param FormInterface $form
     * @param Ilots|null $ilots
     * @param $options
     */
    private function updateCultureField(FormInterface $form, ?Ilots $ilots, $options)
    {
        if(is_null($ilots)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'culture',
                EntityType::class,
                null,
                [
                    'class' => Cultures::class,
                    'mapped' => false,
                    'choices' => [],
                    'required' => false,
                    'auto_initialize' => false,
                    'placeholder' => 'Selectionner un ilot avant de choisir une culture'
                ]
            );
        } else {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('culture',
                EntityType::class,
                null,
                [
                    'class' => Cultures::class,
                    'label' => 'Culture::',
                    'choice_label' => function(Cultures $cultures) {
                        return $cultures->getName()->getName() . ' ' . $cultures->getSize() . ' ha - ' . ($cultures->getPrecedent() ? $cultures->getPrecedent()->getName() : '') . ' - ' . ($cultures->getEffluent() ? $cultures->getEffluent()->getName() : '');
                    },
                    'query_builder' => function(CulturesRepository $cr) use ($ilots, $options) {
                        return $cr->findByIlot($ilots->getId(), date('Y'), true );
                    },
                    'auto_initialize' => false,
                    'placeholder' => 'Selectionner une culture'
                ]
            );
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addField($form->getParent());
            }
        );


        $form->add($builder->getForm());
    }

    /**
     * Add select Quantity
     * @param FormInterface $form
     */
    private function addField(FormInterface $form)
    {
        $form
            ->add('effiency_prev', NumberType::class, [
                'label' => 'Rendement précédent ( Quintaux )',
                'required' => false
            ])
            ->add('qty_azote_add_prev', NumberType::class, [
                'label' => 'Quantité Azote apportée sur le précédent:',
                'label_attr' => [
                    'id' => 'quantityLabel'
                ],
                'required' => true
            ])
            ->add('date_implantation_planned', DateType::class, [
                'label' => 'Période d\'implantation envisagée',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Continuer'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'exploitation' => null
        ]);
    }
}

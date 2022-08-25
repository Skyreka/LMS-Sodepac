<?php

namespace App\Domain\Intervention\Form;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Repository\IndexCulturesRepository;
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
                'choice_label' => function(IndexCultures $indexCultures) {
                    return $indexCultures->getName();
                },
                'query_builder' => function(IndexCulturesRepository $icr) use ($options) {
                    return $icr->findActiveCultureByExploitation($options['user']->getExploitation(), true);
                },
                'placeholder' => 'Sélectionner votre culture'
            ]);
        
        $builder->get('selectCulture')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->addCultureField($form->getParent(), $form->getData(), $options);
            }
        );
        
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) use ($options) {
                $data = $event->getData();
                $form = $event->getForm();
                $this->addCultureField($form, null, null);
            }
        );
    }
    
    private function addCultureField(FormInterface $form, ?IndexCultures $indexCultures, $options)
    {
        if(is_null($indexCultures)) {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
                'cultures',
                HiddenType::class,
                null,
                [
                    'auto_initialize' => false,
                    'mapped' => false
                ]
            );
        } else {
            $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('cultures',
                EntityType::class,
                null,
                [
                    'class' => Cultures::class,
                    'label' => 'Cultures incluant votre culture choisi:',
                    'choice_label' => function(Cultures $culture) {
                        return $culture->getName()->getName() . ' ' . $culture->getSize() . ' ha - ' . $culture->getIlot()->getName();
                    },
                    'query_builder' => function(CulturesRepository $cr) use ($indexCultures, $options) {
                        return $cr->findByIndexCultureInProgress($indexCultures->getId(), $options['user']->getExploitation(), true);
                    },
                    'auto_initialize' => false,
                    'mapped' => false,
                    'placeholder' => 'Choisir culture',
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

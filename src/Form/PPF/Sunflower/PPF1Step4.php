<?php

namespace App\Form\PPF\Sunflower;

use App\Entity\IndexEffluents;
use App\Entity\PPF;
use App\Repository\IndexEffluentsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PPF1Step4 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('effluent', EntityType::class, [
                'class' => IndexEffluents::class,
                'label' => 'Effluent',
                'choice_label' => function(IndexEffluents $indexEffluents) {
                    return $indexEffluents->getName() . ' - Teneur en azote : ' . $indexEffluents->getNitrogenContent();
                }
            ])
            ->add('azote', TextType::class, [
                'mapped' => false
            ])
            ->add('qty_ependu', TextType::class, [
                'label' => 'Quantité epandue ( Tou m3 / Ha )'
            ])
            ->add('date_spreading', DateType::class, [
                'label' => 'Date d\'épandage'
            ])
            ->add('coefficient_equivalence', TextType::class, [
                'label' => 'Coefficient équivalence'
            ])
            ->add('qty_azote_add', NumberType::class, [
                'label' => 'Quantité d\'azote total à apporter'
            ])
        ;

        //-- Event Listener on select product
        $builder->get('effluent')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $this->updateAzote( $form->getParent(), $form->getData(), $options);
            }
        );
    }

    /**
     * Update NPK
     * @param FormInterface $form
     * @param IndexEffluents|null $effluents
     * @param $options
     */
    private function updateAzote(FormInterface $form, ?IndexEffluents $effluents, $options)
    {
        if (is_null($effluents)) {
            $form
                ->add('azote', TextType::class, [
                    'mapped' => false,
                    'label' => 'Teneur en Azote'
                ])
            ;
        } else {
            $form
                ->add('azote', TextType::class, [
                    'mapped' => false,
                    'label' => 'Teneur en Azote',
                    'attr' => [
                        'value' => $effluents->getNitrogenContent()
                    ]
                ])
            ;
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PPF::class
        ]);
    }
}

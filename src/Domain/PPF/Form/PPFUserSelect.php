<?php

namespace App\Domain\PPF\Form;

use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\PPF\Entity\PPF;
use App\Domain\Recommendation\Entity\Recommendations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Class PPFUserSelect
 * @package App\Form\PPF
 */
class PPFUserSelect extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('exploitation', Select2EntityType::class, [
                'remote_route' => 'admin_ppf_select_data',
                'class' => Exploitation::class,
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'fr',
                'placeholder' => 'Choisir un utilisateur',
                'help' => 'Utilisateur ayant un PACK FULL uniquement.'
            ])
            ->add('types', ChoiceType::class, [
                'choices' => $this->getTypes(),
                'mapped' => false
            ]);
    }
    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recommendations::class
        ]);
    }
    
    /**
     * @return array
     */
    private function getTypes()
    {
        $choices = PPF::TYPES;
        $output  = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}

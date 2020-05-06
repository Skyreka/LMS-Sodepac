<?php

namespace App\Form;

use App\Entity\Recommendations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationMentionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mention', ChoiceType::class, [
                'label' => 'Méthode alternative',
                'choices' => [
                    'Ne pas choisir de méthode' => null,
                    '2017-004 Lutter contre les chenilles foreuses de fruit en vergers au moyen du virus de la granulose' => '2017-004',
                    '2017-005 Lutter contre les Lépidoptères ravageurs en vergers au moyen de diffuseurs de phéromones pour la confusuion sexuelle' => '2017-005',
                    '2017-006 Lutter contre la pyrale du maïs au moyen de lâchers de trichogrammes' => '2017-006',
                    '2017-008 Lutter contre divers bioagresseurs au moyen d\'un produit de biocontrôle à base de soufre' => '2017-008',
                    '2017-023 Substituer des produits anti-limaces à base de métaldéhyde par des produits de biocontrôle molluscides d\'origine naturelle' => '2017-023',
                    '2017-026 Lutter contre les champignons tellurique au moyen d\'un produit de biocontrôle' => '2017-026',
                    '2017-028 Lutter contre divers champignons pathogènes du feuillage au moyen d\'un produit de biocontrôle' => '2017-028',
                    '2018-034 Lutter contre les chenilles phytophages au moyen d\'un produit de biocontrôle contenant du Bacillus thuringiensis' => '2018-034',
                    '2018-037 Lutter contre les taupins du maïs au moyen d\'un produit de biocontrôle' => '2018-037',
                    '2018-039 Lutter contre les insectes piqueurs au moyen d\une poudre minérale de biocontrôle' => '2018-039',
                    '2018-040 Eviter les traitements insecticides au stockage en conservant les grains dans des saches hermetiques' => '2018-040',
                    '2018-041 Lutter contre les mouches dans les vergers et la vigne au moyen de pièges listés comme produits de biocontrôle' => '2018-041',
                    '2018-044 Réduire les traitements fongicides et insecticides en culture au moyen d\'une huile essentielle de biocontrôle' => '2018-044',
                    '2019-018 Réduire la consommation de fongicides ciblant les maladies du feuillage du blé au moyen d\'un adjuvant' => '2019-018',
                    '2020-007 Lutter contre des maladies fongiques au moyen d\'un stimulateur de défense des plantes' => '2020-007',
                    '2020-009 Lutter contre les tordeuses en vigne au moyen de diffuseurs de phéromones pour la confusion sexuelle' => '2020-009',
                    '2020-038 Lutter contre les insectes piqueurs au moyen d\'un produit de biocontrôle à base d\'huile minérale' => '2020-038',
                ]
            ])
            ->add('mention_txt', TextareaType::class, [
                'label' => 'Champs Libre',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'forms',
            'data_class' => Recommendations::class,
        ]);
    }
}

<?php

namespace App\Command;

use App\Entity\IndexCanevas;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cocur\Slugify\Slugify;

class CanevasIndexCommand extends command
{
    protected static $defaultName = 'app:importCanevasIndex';
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import Index Canevas to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");


        // Declaration des tableaux
        $indexCanevas = [
            'Amandier',
            'Amandier Methodes Alternatives',
            'Avoine',
            'Ble',
            'Chataigner',
            'Colza',
            'Feverole',
            'Fourrages',
            'Mais',
            'Melon',
            'Noisetier',
            'Noisetier Methodes Alternatives',
            'Noyer Conventionnel',
            'Noyer Methodes Alternatives',
            'Orge',
            'Pecher',
            'Pois Hiver',
            'Pommier',
            'Pommier Methodes Alternatives',
            'Prune Dente',
            'Prune Dente Methodes Alternatives',
            'Soja',
            'Sorgho',
            'Tournesol',
            'Triticale',
            'Triticale Automne',
            'Vigne',
            'Vigne Bio',
            'Autre',
        ];

        $slugify = new Slugify();
        $v = 0;

        // Boucle par line du csv
        foreach ($indexCanevas as $name) {
            $v = $v + 1;
            dump( $v );

            // Create canevas
            $indexCanevas = new IndexCanevas();
            $indexCanevas->setName( $name );
            if ( $name == 'Autre' ) {
                $indexCanevas->setSlug( 'other' );
            } else {
                $indexCanevas->setSlug( $slugify->slugify( $name ) );
            }

            $em->persist( $indexCanevas );
        }

        $em->flush();
        // On donne des information des résultats
        $output->writeln($v  . ' canevas importées');
        return 1;
    }
}

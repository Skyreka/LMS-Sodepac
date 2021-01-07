<?php

namespace App\Command;

use App\Entity\IndexCanevas;
use App\Entity\Warehouse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WarehouseCommand extends command
{
    protected static $defaultName = 'app:importWareHouse';
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
            ->setDescription('Import Warehouse to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");


        // Declaration des tableaux
        $warehouses = [
            [
                'Libos',
                'Libos',
                'e.agenor@sodepacc.fr'
            ],
            [
                'Castelfrand',
                'Castelfranc',
                'magasin.castelfranc@sodepacc.fr'
            ],
            [
                'Cancon',
                'Cancon',
                'magasin.cancon@sodepacc.fr'
            ],
            [
                'Montpezat',
                'Montpezat',
                'magasin.montpezat@sodepacc.fr'
            ],
            [
                'Villefranche',
                'Villefranche du Périgord',
                'magasin.villefranche@sodepacc.fr'
            ],
        ];

        $v = 0;

        // Boucle par line du csv
        foreach ($warehouses as $index) {
            $v = $v + 1;
            dump( $v );

            // Create warehouse

            $warehouse = new Warehouse();
            $warehouse
                ->setName($index[0])
                ->setAddress($index[1])
                ->setEmail($index[2]);

            $em->persist( $warehouse );
        }

        $em->flush();
        // On donne des information des résultats
        $output->writeln($v  . ' dépôt importées');
        return 1;
    }
}

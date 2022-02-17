<?php

namespace App\Command;

use App\Entity\IndexGrounds;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TreeHouse\Slugifier\Slugifier;

class GroundsCommand extends command
{
    protected static $defaultName = 'app:importGrounds';
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
            ->setDescription('Import Grounds to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'grounds.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $grounds = [];

        //Declaration de slugify
        $slugify = new Slugifier();

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            // On sauvegarde le product && Prend uniquement juste une donnée
            if (key_exists(0, $line))
            {
                $ground = new IndexGrounds();
                $ground->setName( $line[0] );
                $ground->setSlug( $slugify->slugify( $line[0] ) );
                $grounds[$line[0]] = $grounds;
                $em->persist($ground);
            }
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($grounds) . ' type de sols importées');
        return 1;
    }
}
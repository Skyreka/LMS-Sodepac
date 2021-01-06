<?php

namespace App\Command;

use App\Entity\Products;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TreeHouse\Slugifier\Slugifier;

class ProductsSodepacCommand extends Command
{
    protected static $defaultName = 'app:importProductsSodepac';
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
            ->setDescription('Import Products Sodepac to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'sodepac.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $products = [];
        $applications = [];

        $v = 0;

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $v = $v + 1;
            dump( $v );
            dump($line[1]);
            $line = explode(',', $line);

            if ( !empty( $line[0] )) {
                //Index
                $name = $line[0].'(S)';
                $slug = $line[1];

                // On sauvegarde le product
                    //-- Add new products
                    $product = new Products();
                    $product
                        ->setName($name)
                        ->setSlug($slug);
                    $em->persist($product);
            }
        }

        dump( $products );
        $em->flush();
        // On donne des information des résultats
        $output->writeln('produits Sodepac importés');
        //$output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

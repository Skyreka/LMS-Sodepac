<?php

namespace App\Command;

use App\Entity\Doses;
use App\Entity\Products;
use App\Entity\ProductsCategory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategorysCommand extends Command
{
    protected static $defaultName = 'app:importProducts';
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
            ->setDescription('Import Products & Doses to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'to_import.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $products = [];
        $applications = [];

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            // On sauvegarde le product && Prend uniquement juste une donnée
            if (!key_exists($line[2], $products) && !in_array($line[2], $products)) {
                //-- Add new products
                $product = new Products();
                $product->setName($line[2]);
                $products[$line[2]] = $products;
                $em->persist($product);
            }
            if (!key_exists($line[17], $applications)) {
                //-- Add new doses
                $doses = new Doses();
                $doses->setProduct( $product );
                $doses->setDose($line[17]);
                $doses->setUnit($line[18]);
                $doses->setApplication($line[12]);
                $applications[$line[14]] = $applications;
                $em->persist($doses);
            }
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits importées');
        $output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

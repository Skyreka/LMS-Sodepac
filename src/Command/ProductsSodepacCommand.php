<?php

namespace App\Command;

use App\Entity\Doses;
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
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'productsSodepac.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $products = [];

        //Declaration de slugify
        $slugify = new Slugifier();

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            // On sauvegarde le product && Prend uniquement juste une donnée
                //-- Add new products
                $product = new Products();
                $product->setName('S-'.$line[1]);
                $product->setSlug( $slugify->slugify( 's-'.$line[1] ) );
                $products[$line[1]] = $products;
                $em->persist($product);
                //-- Add new doses
                $doses = new Doses();
                $doses->setProduct( $product );
                $doses->setDose(0);
                $doses->setUnit(' ');
                $doses->setApplication('Cliquez ici');
                $em->persist($doses);
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits importées');
        return 1;
    }
}

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

class ProductsCommand extends Command
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
            ->setDescription('Import Products to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'lex.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $products = [];
        $applications = [];

        //Declaration de slugify
        $slugify = new Slugifier();
        $v = 0;

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $v = $v + 1;
            dump( $v );
            $line = explode(',', $line);

            if ( !empty( $line[3] )) {
                //Index
                $name = $line[3];
                $idLex = $line[4];
                $substance = $line[5];
                $tox = $line[12];
                $riskPhase = $line[14];
                $bio = $line[11];
                $type = $line[13];

                // On sauvegarde le product && Prend uniquement juste une donnée
                if ( !in_array($idLex, $products) ) {

                    array_push( $products, $idLex );

                    //-- Add new products
                    $product = new Products();
                    $product->setName($name);
                    $product->setSlug( $slugify->slugify( $name ) );
                    $product->setCategory( null );
                    $product->setIdLex( $idLex );
                    $product->setSubstance( $substance );
                    $product->setTox( $tox );
                    $product->setRiskPhase( $riskPhase );
                    $product->setBio( $bio );
                    $product->setType( $type );
                    $products[$line[2]] = $products;
                    $em->persist($product);
                }
            }
        }

        dump( $products );
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits importées');
        //$output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

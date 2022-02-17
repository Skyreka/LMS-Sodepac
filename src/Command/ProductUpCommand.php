<?php

namespace App\Command;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TreeHouse\Slugifier\Slugifier;

/**
 * Class ProductUpCommand
 * @package App\Command
 */
class ProductUpCommand extends Command
{
    protected static $defaultName = 'app:updateProducts';
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ProductsRepository
     */
    private $pr;

    public function __construct(ContainerInterface $container, ProductsRepository $pr )
    {
        parent::__construct();
        $this->container = $container;
        $this->pr = $pr;
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
                $dar = $line[8];
                $znt = $line[9];
                $dre = $line[10];
                $tox = $line[12];
                $riskPhase = $line[14];
                $securityMention = $line[15];
                $dangerMention = $line[16];
                $bio = $line[11];
                $type = $line[13];

                // On sauvegarde le product && Prend uniquement juste une donnée
                if ( !in_array($idLex, $products) ) {
                    array_push( $products, $idLex );

                    $product = $this->pr->findOneBy( ['id_lex' => $idLex ] );
                    $product->setDar( $dar );
                    $product->setZnt( $znt );
                    $product->setDre( $dre );
                    $product->setSecurityMention( $securityMention );
                    $product->setDangerMention( $dangerMention );
                }
            }
        }

        dump( $products );
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits mis à jour');
        //$output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

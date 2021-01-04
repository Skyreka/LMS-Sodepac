<?php

namespace App\Command;

use App\Entity\Doses;
use App\Repository\CulturesRepository;
use App\Repository\IndexCulturesRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DosesCommand extends Command
{
    protected static $defaultName = 'app:importDoses';
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ProductsRepository
     */
    private $pr;

    /**
     * @var IndexCulturesRepository
     */
    private $icr;

    public function __construct(ContainerInterface $container, ProductsRepository $pr, IndexCulturesRepository $icr )
    {
        parent::__construct();
        $this->container = $container;
        $this->pr = $pr;
        $this->icr = $icr;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import Doses to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
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
        $v = 0;

        //-- Add new products
        $emptyDose = new Doses();
        $emptyDose->setDose( NULL );
        $emptyDose->setProduct( NULL );
        $emptyDose->setApplication( 'Définir une dose' );

        $em->persist( $emptyDose );

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $v = $v + 1;
            dump( $v );
            $line = explode(',', $line);

            if ( !empty($line[0]) ) {
                //Index
                $doseIndex = $line[6];
                dump( $doseIndex );
                if ( $doseIndex == NULL ) {
                    $doseIndex = 0;
                }
                $doseUnit = $line[7];
                dump( $doseUnit );
                if ( $doseUnit == NULL ) {
                    $doseUnit = 0;
                }
                $productIdLex = $line[4];
                $cultureIdLex = $line[1];
                $znt = $line[9];
                $dre = $line[10];
                $dar = $line[8];
                $dangerMention = $line[16];
                $riskMention = $line[14];
                $securityMention = $line[15];
                $application = $line[2];


                $product = $this->pr->findOneBy( ['id_lex' => $productIdLex ] );
                $indexCulture = $this->icr->findOneBy( ['id_lex' => $cultureIdLex ] );

                //-- Add new products
                $dose = new Doses();
                $dose->setDose( $doseIndex );
                $dose->setUnit( $doseUnit );
                $dose->setProduct( $product );
                $dose->setApplication( $application );
                $dose->setIndexCulture( $indexCulture );
                $dose->setZNT( $znt );
                $dose->setDAR( $dar );
                $dose->setDRE( $dre );
                $dose->setDangerMention( $dangerMention );
                $dose->setRiskMention( $riskMention );
                $dose->setSecurityMention( $securityMention );

                $em->persist($dose);
            }
        }

        $em->flush();
        // On donne des information des résultats
        $output->writeln($v . ' doses importées');
        return 1;
    }
}

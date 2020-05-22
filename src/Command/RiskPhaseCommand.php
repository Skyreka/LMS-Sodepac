<?php

namespace App\Command;

use App\Entity\Products;
use App\Entity\RiskPhase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RiskPhaseCommand extends Command
{
    protected static $defaultName = 'app:importRiskPhase';
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
            ->setDescription('Import Risk Phases to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'RiskPhase.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            //-- Find product
            $repo = $em->getRepository('App:Products');
            $product = $repo->findOneBy(['name' => $line[1]]);
            //-- Add new risk Phase
                $riskPhase = new RiskPhase();
                $riskPhase->setShortWording($line[2]);
                $riskPhase->setLongWording($line[3]);
                $riskPhase->setProduct($product);
                $em->persist($riskPhase);
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln('Phases de risques importés avec succès');
        return 1;
    }
}

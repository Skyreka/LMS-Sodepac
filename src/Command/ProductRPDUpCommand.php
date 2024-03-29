<?php

namespace App\Command;

use App\Domain\Product\Repository\ProductsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TreeHouse\Slugifier\Slugifier;

/**
 * Class ProductUpCommand
 * @package App\Command
 */
class ProductRPDUpCommand extends Command
{
    protected static $defaultName = 'app:updateRPDProducts';
    
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ProductsRepository $pr
    )
    {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Import Products to DB');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // On récupere le csv
        $csv   = dirname($this->container->get('kernel')->getProjectDir()) .  DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'rpd.csv';
        $lines = explode("\n", file_get_contents($csv));
        

        //Declaration de slugify
        $v       = 0;
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $v = $v + 1;
            dump($v);
            $line = explode(';', $line);
            // TEST
            
            //Index
            $amm = $line[0];
            $rpd = $line[1];
            
            $products = $this->pr->findBy(['amm' => $amm]);
            foreach( $products as $product ) {
                $product->setRPD(floatval($rpd));
            }
        }
        
        dump($products);
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits mis à jour');
        //$output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

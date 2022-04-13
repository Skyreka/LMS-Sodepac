<?php

namespace App\Command;

use App\Domain\Product\Entity\Products;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TreeHouse\Slugifier\Slugifier;

class ProductsSodepacNPKCommand extends Command
{
    protected static $defaultName = 'app:productSodepadNPK';

    
    public function __construct(private readonly ContainerInterface $container)
    {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Import Product Sodepac With NPK');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // On récupere le csv
        $csv   = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'product_sodepac_npk.csv';
        $lines = explode("\n", file_get_contents($csv));
        
        // Declaration des tableaux
        $products     = [];
        $applications = [];
        
        $slugify = new Slugifier();
        
        $v = 0;
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $v    = $v + 1;
            $line = explode(';', $line);
            
            if(! empty($line[0])) {
                //Index
                $name = $line[0] . '(S)';
                $n    = $line[1];
                $p    = $line[2];
                $k    = $line[3];
                
                dump($n);
                dump($p);
                dump($k);
                
                // On sauvegarde le product
                //-- Add new products
                $product = new Products();
                $product
                    ->setName($name)
                    ->setSlug($slugify->slugify($name))
                    ->setN(floatval($n))
                    ->setP(floatval($p))
                    ->setK(floatval($k));
                $em->persist($product);
            }
        }
        
        dump($products);
        $em->flush();
        // On donne des information des résultats
        $output->writeln('produits Sodepac importés');
        //$output->writeln(count($applications) . ' doses importées');
        return 1;
    }
}

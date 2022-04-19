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
class ProductAMMUpCommand extends Command
{
    protected static $defaultName = 'app:updateAMMProducts';
    
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
        $csv   = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'amm.csv';
        $lines = explode("\n", file_get_contents($csv));
        
        // Declaration des tableaux
        $products = [];
        
        //Declaration de slugify
        $v       = 0;
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $v = $v + 1;
            dump($v);
            $line = explode(';', $line);
            
            //Index
            $idLex = $line[0];
            $amm = $line[1];
            
            // On sauvegarde le product && Prend uniquement juste une donnée
            if(! empty($line[0])) {
                if(! in_array($idLex, $products)) {
                    array_push($products, $idLex);
                    $product = $this->pr->findOneBy(['id_lex' => $idLex]);
                    $product->setRPD(floatval($amm));
                }
            }
        }
        $em->flush();
        
        // On donne des information des résultats
        $output->writeln(count($products) . ' produits mis à jour');
        return 1;
    }
}

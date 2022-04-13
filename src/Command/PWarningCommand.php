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
class PWarningCommand extends Command
{
    protected static $defaultName = 'app:PWarningCommand';
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ProductsRepository
     */
    private $pr;
    
    public function __construct(ContainerInterface $container, ProductsRepository $pr)
    {
        parent::__construct();
        $this->container = $container;
        $this->pr        = $pr;
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Add warning message to product');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // On récupere le csv
        $csv   = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'warning.csv';
        $lines = explode("\n", file_get_contents($csv));
        
        // Declaration des tableaux
        $products = [];
        
        //Declaration de slugify
        $slugify = new Slugifier();
        $v       = 0;
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $v = $v + 1;
            dump($v);
            $line = explode(',', $line);
            
            if(! empty($line[0])) {
                //Index
                $idLex          = $line[0];
                $warningMessage = $line[1];
                
                // On sauvegarde le product && Prend uniquement juste une donnée
                if(! in_array($idLex, $products)) {
                    array_push($products, $idLex);
                    
                    $product = $this->pr->findOneBy(['id_lex' => $idLex]);
                    
                    if(! empty($warningMessage)) {
                        $product->setWarningMention($warningMessage);
                    } else {
                        $product->setWarningMention(null);
                    }
                }
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

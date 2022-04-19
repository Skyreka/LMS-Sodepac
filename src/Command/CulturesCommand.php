<?php

namespace App\Command;

use App\Domain\Index\Entity\IndexCultures;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CulturesCommand extends command
{
    protected static $defaultName = 'app:importCultures';
    
    public function __construct(private readonly ContainerInterface $container)
    {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Import Cultures to DB');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // On récupere le csv
        $csv   = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'lex.csv';
        $lines = explode("\n", file_get_contents($csv));
        
        // Declaration des tableaux
        $cultures = [];
        $slugify  = new Slugify();
        $v        = 0;
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $line = explode(',', $line);
            $v    = $v + 1;
            dump($v);
            
            if(! empty($line[1])) {
                
                // Define Index
                $name  = $line[0];
                $idLex = $line[1];
                
                if(! in_array($name, $cultures)) {
                    array_push($cultures, $name);
                    
                    $culture = new IndexCultures();
                    $culture->setIdLex($idLex);
                    $culture->setName($name);
                    $culture->setIsDisplay(1);
                    $culture->setPermanent(0);
                    $culture->setSlug($slugify->slugify($name));
                    
                    $em->persist($culture);
                }
            }
        }
        
        // Debug
        dump($cultures);
        
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($cultures) . ' cultures importées');
        return 1;
    }
}

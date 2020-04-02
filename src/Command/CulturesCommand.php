<?php

namespace App\Command;

use App\Entity\IndexCultures;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CulturesCommand extends command
{
    protected static $defaultName = 'app:importCultures';
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
            ->setDescription('Import Cultures to DB')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'cultures.csv';
        $lines = explode("\n", file_get_contents($csv));

        // Declaration des tableaux
        $cultures = [];

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            // On sauvegarde le product && Prend uniquement juste une donnée
            if (key_exists(1, $line))
            {
                $culture = new IndexCultures();
                $culture->setSlug($line[0]);
                $culture->setName($line[1]);
                $culture->setPermanent($line[2]);
                $cultures[$line[1]] = $cultures;
                $em->persist($culture);
            }
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln(count($cultures) . ' cultures importées');
        return 1;
    }
}
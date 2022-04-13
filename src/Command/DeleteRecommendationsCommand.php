<?php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DeleteRecommendationsCommand extends Command
{
    protected static $defaultName = 'app:deleteRecommendations';
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
            ->setDescription('Delete recommendations on status 0');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        $repo            = $em->getRepository('App:Recommendations');
        $recommendations = $repo->findBy(['status' => 0]);
        foreach($recommendations as $recommendation) {
            $em->remove($recommendation);
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln('Catalogues en status 0 supprimés avec succès');
        return 1;
    }
}

<?php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DeleteTemp extends Command
{
    protected static $defaultName = 'app:deleteTemp';
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
            ->setDescription('Delete all temp in order & contract byt');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        $repo   = $em->getRepository('App:Orders');
        $orders = $repo->findBy(['status' => 0]);
        foreach($orders as $order) {
            $em->remove($order);
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln('Order en status 0 supprimé avec succès');
        return 1;
    }
}

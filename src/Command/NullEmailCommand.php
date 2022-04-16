<?php

namespace App\Command;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Product\Repository\ProductsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NullEmailCommand extends Command
{
    protected static $defaultName = 'app:nullEmail';
    
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly UsersRepository $ur
    )
    {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Create Fake Email');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // Boucle par line du csv
        foreach($this->ur->findBy(['email' => '']) as $user) {
            dump($user->getId());
            $id    = substr(md5(uniqid(rand(), true)), 0, 5);
            $email = $id . '@sodepac.fr';
            $user->setEmail($email);
        }
        
        $em->flush();
        return 1;
    }
}

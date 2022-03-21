<?php

namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportUserFromTechCommand extends command
{
    protected static $defaultName = 'app:importUserFromTech';
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
            ->setDescription('Import Customers to DB')
            ->addArgument('email', InputArgument::REQUIRED, 'Email du technicien')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On récupere le csv
        $csv = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR .$input->getArgument('email').'.csv';
        $lines = explode("\n", file_get_contents($csv));

        //On recupere le technician
        $repo = $em->getRepository('App:Users');
        $technician = $repo->findOneBy(['email' => $input->getArgument('email')]);

        // Declaration des tableaux
        $customers = [];

        // Boucle par line du csv
        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            // On sauvegarde le client && Prend uniquement juste une donnée
            if (key_exists(1, $line))
            {
                $customer = new Users();
                $customer->setFirstname($line[2]);
                $customer->setLastname(' ');
                $customer->setEmail($line[9]);
                $customer->setphone($line[7].' '.$line[8]);
                $customer->setCity($line[6]);
                $customer->setStatus('ROLE_USER');
                $customer->setTechnician($technician);
                $customer->setCertificationPhyto($line[10]);
                $customers[$line[1]] = $customers;
                $em->persist($customer);
            }
        }
        $em->flush();
        // On donne des information des résultats
        $output->writeln('Clients importées avec succès');
        return 1;
    }
}

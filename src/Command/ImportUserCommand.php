<?php

namespace App\Command;

use App\Domain\Auth\Users;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportUserCommand extends Command
{
    protected static $defaultName = 'app:importUser';
    
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly UserPasswordEncoderInterface $passwordEncoder
    )
    {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Import Customers to DB from CSV file')
            ->addArgument('default_password', InputArgument::REQUIRED, 'Default password for user imported');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* @var $em EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        
        // yolo
        ini_set("memory_limit", "-1");
        
        // On récupere le csv
        $csv   = dirname($this->container->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'users.csv';
        $lines = explode("\n", file_get_contents($csv));
        
        
        // Boucle par line du csv
        foreach($lines as $k => $line) {
            $line = explode(';', $line);
            dump($line);
            // On sauvegarde le client && Prend uniquement juste une donnée
            if(key_exists(0, $line)) {
                //var
                $email      = ! empty($line[7]) ? $line[7] : "";
                $address    = ! empty($line[3]) ? $line[3] : "";
                $postalCode = ! empty($line[4]) ? $line[4] : "";
                $city       = ! empty($line[5]) ? $line[5] : "";
                $phone      = ! empty($line[6]) ? $line[6] : "";
                $certif     = ! empty($line[8]) ? $line[8] : "";
                $company    = ! empty($line[2]) ? $line[2] : "";
                $lastname   = ! empty($line[1]) ? $line[1] : "";
                $firstname  = ! empty($line[0]) ? $line[0] : "";
                
                $user = new Users();
                
                // import default information
                $user
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setCompany($company)
                    ->setAddress($address)
                    ->setPostalCode($postalCode)
                    ->setCity($city)
                    ->setPhone($phone)
                    ->setCertificationPhyto($certif)
                    ->setPassword($this->password->encodePassword($user, $input->getArgument('default_password')))
                    ->setStatus('ROLE_USER')
                    ->setPack('DISABLE');
                
                // Null email
                if(empty($email)) {
                    $id    = substr(md5(uniqid(rand(), true)), 0, 5);
                    $email = $id . '@saslarrieu.fr';
                    $user->setEmail($email);
                } else {
                    $user->setEmail($email);
                }
                
                $em->persist($user);
                $em->flush();
            }
        }
        // On donne des information des résultats
        $output->writeln('Clients importés avec succès');
        return 1;
    }
}

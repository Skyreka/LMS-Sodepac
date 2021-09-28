<?php

    namespace App\Command;

    use App\Entity\Doses;
    use App\Repository\CulturesRepository;
    use App\Repository\IndexCulturesRepository;
    use App\Repository\ProductsRepository;
    use App\Repository\UsersRepository;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;
    use Symfony\Component\DependencyInjection\ContainerInterface;

    class NullEmailCommand extends Command
    {
        protected static $defaultName = 'app:nullEmail';
        /**
         * @var ContainerInterface
         */
        private $container;

        /**
         * @var ProductsRepository
         */
        private $ur;


        public function __construct(ContainerInterface $container, UsersRepository $ur )
        {
            parent::__construct();
            $this->container = $container;
            $this->ur = $ur;
        }

        protected function configure()
        {
            $this
                ->setDescription('Create Fake Email')
            ;
        }

        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $em = $this->container->get('doctrine')->getManager();

            // yolo
            ini_set("memory_limit", "-1");

            // Boucle par line du csv
            foreach ($this->ur->findBy( ['email' => '' ] ) as $user) {
                dump( $user->getId() );
                $id = substr( md5(uniqid(rand(), true)), 0, 5);
                $email = $id.'@sodepac.fr';
                $user->setEmail( $email );
            }

            $em->flush();
            return 1;
        }
    }

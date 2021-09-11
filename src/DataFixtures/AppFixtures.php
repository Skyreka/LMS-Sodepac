<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new Users();
        $admin->setFirstname( 'Admin' );
        $admin->setEmail('admin@sodepac.dev');
        $admin->setPhone('+337803135363');
        $password = $this->encoder->encodePassword( $admin, 'dev');
        $admin->setPassword( $password );
        $admin->setStatus('ROLE_ADMIN');
        $admin->setIsActive( 1 );

        $tech = new Users();
        $tech->setFirstname( 'Tech' );
        $tech->setEmail('tech@sodepac.dev');
        $tech->setPhone('+337803135363');
        $password = $this->encoder->encodePassword( $tech, 'dev');
        $tech->setPassword( $password );
        $tech->setStatus('ROLE_TECHNICIAN');
        $tech->setIsActive( 1 );

        $user = new Users();
        $user->setFirstname( 'User' );
        $user->setEmail('user@sodepac.dev');
        $user->setPhone('+337803135363');
        $password = $this->encoder->encodePassword( $user, 'dev');
        $user->setPassword( $password );
        $user->setStatus('ROLE_USER');
        $user->setIsActive( 1 );
        $user->setTechnician( $tech );

        $sales = new Users();
        $sales->setFirstname( 'sales' );
        $sales->setEmail('sales@sodepac.dev');
        $sales->setPhone('+337803135363');
        $password = $this->encoder->encodePassword( $sales, 'dev');
        $sales->setPassword( $password );
        $sales->setStatus('ROLE_SALES');
        $sales->setIsActive( 1 );

        $pricing = new Users();
        $pricing->setFirstname( 'princing' );
        $pricing->setEmail('pricing@sodepac.dev');
        $pricing->setPhone('+33700000000');
        $password = $this->encoder->encodePassword( $pricing, 'dev');
        $pricing->setPassword( $password );
        $pricing->setStatus('ROLE_PRICING');
        $pricing->setIsActive( 1 );


        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($sales);
        $manager->persist($tech);
        $manager->persist($user);
        $manager->flush();
    }
}

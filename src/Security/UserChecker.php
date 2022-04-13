<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function checkPreAuth(UserInterface $user)
    {
    }
    
    public function checkPostAuth(UserInterface $user)
    {
        $user->setLastActivity(new \DateTime());
        $this->em->flush();
    }
}

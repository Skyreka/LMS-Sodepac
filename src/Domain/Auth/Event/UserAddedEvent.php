<?php

namespace App\Domain\Auth\Event;

use App\Domain\Auth\Users;
use App\Domain\Order\Entity\Orders;

class UserAddedEvent
{
    public function __construct(private readonly Users $user)
    {
    }
    
    /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }
    
}

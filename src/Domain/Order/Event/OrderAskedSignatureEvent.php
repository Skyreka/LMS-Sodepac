<?php

namespace App\Domain\Order\Event;

use App\Domain\Order\Entity\Orders;

class OrderAskedSignatureEvent
{
    public function __construct(private readonly Orders $order)
    {
    }
    
    public function getOrder(): Orders
    {
        return $this->order;
    }
    
}

<?php

namespace App\Domain\Signature\Event;

use App\Domain\Auth\Users;
use App\Domain\Order\Entity\Orders;
use App\Domain\Signature\Entity\Signature;
use App\Domain\Signature\Entity\SignatureOtp;

class SignatureSignedEvent
{
    public function __construct(private readonly Signature $signature)
    {
    }
    
    /**
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->signature;
    }
}

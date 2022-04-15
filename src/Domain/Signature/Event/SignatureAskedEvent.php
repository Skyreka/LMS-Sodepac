<?php

namespace App\Domain\Signature\Event;

use App\Domain\Auth\Users;
use App\Domain\Order\Entity\Orders;
use App\Domain\Signature\Entity\Signature;
use App\Domain\Signature\Entity\SignatureOtp;

class SignatureAskedEvent
{
    public function __construct(private readonly SignatureOtp $signatureOtp)
    {
    }
    
    /**
     * @return SignatureOtp
     */
    public function getSignatureOtp(): SignatureOtp
    {
        return $this->signatureOtp;
    }
}

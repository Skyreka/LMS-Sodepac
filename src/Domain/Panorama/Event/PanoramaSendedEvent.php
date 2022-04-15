<?php

namespace App\Domain\Panorama\Event;

use App\Domain\Auth\Users;
use App\Domain\Panorama\Entity\Panorama;

class PanoramaSendedEvent
{
    public function __construct(
        private readonly Panorama $panorama,
        private readonly Users $receiver
    )
    {
    }
    
    /**
     * @return Panorama
     */
    public function getPanorama(): Panorama
    {
        return $this->panorama;
    }
    
    /**
     * @return Users
     */
    public function getReceiver(): Users
    {
        return $this->receiver;
    }
    
}

<?php

namespace App\Infrastructure\Queue;

use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use DateTimeInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EnqueueMethod
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }
    
    public function enqueue(
        string $service,
        string $method,
        array $params = [],
        DateTimeInterface $date = null
    ): void
    {
        $stamps = [];
        // Le service doit être appelé avec un délai
        if(null !== $date) {
            $delay = 1000 * ($date->getTimestamp() - time());
            if($delay > 0) {
                $stamps[] = new DelayStamp($delay);
            }
        }
        $this->bus->dispatch(new ServiceMethodMessage($service, $method, $params), $stamps);
    }
}
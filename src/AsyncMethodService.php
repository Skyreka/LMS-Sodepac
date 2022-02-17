<?php

namespace App;

use App\Message\ServiceMethodCallMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class AsyncMethodService
{
    private MessageBusInterface $messageBus;

    public function __construct( MessageBusInterface $messageBus )
    {
        $this->messageBus = $messageBus;
    }

    public function async( string $serviceName, string $methodName, array $params ) {
        $this->messageBus->dispatch( new ServiceMethodCallMessage(
            $serviceName,
            $methodName,
            $params
        ));
    }
}

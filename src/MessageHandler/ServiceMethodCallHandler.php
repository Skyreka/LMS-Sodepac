<?php

namespace App\MessageHandler;

use App\Message\ServiceMethodCallMessage;
use App\Service\EmailNotifier;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ServiceMethodCallHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{
    private ContainerInterface $container;

    public function __construct( ContainerInterface $container )
    {
        $this->container = $container;
    }

    public function __invoke( ServiceMethodCallMessage $message)
    {
        $callable = [
            $this->container->get( $message->getServiceName() ),
            $message->getMethodName()
        ];
        call_user_func_array($callable, $message->getParams());
    }

    public static function getSubscribedServices ()
    {
        return [
            EmailNotifier::class => EmailNotifier::class
        ];
    }
}

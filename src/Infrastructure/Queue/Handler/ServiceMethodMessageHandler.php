<?php
declare(strict_types=1);

namespace App\Infrastructure\Queue\Handler;

use App\Infrastructure\Mailing\MailerService;
use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ServiceMethodMessageHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }
    
    public function __invoke(ServiceMethodMessage $message): void
    {
        $callable = [
            $this->container->get($message->getServiceName()),
            $message->getMethod()
        ];
        
        call_user_func_array($callable, $message->getParams());
    }
    
    public static function getSubscribedServices(): array
    {
        return [
            MailerService::class => MailerService::class
        ];
    }
}

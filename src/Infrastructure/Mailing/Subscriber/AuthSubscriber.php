<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Auth\Event\UserAddedEvent;
use App\Domain\Auth\Event\UserIdentityAddedEvent;
use App\Infrastructure\Mailing\MailerService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerService $mailer,
        private readonly ParameterBagInterface $bag
    )
    {
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            UserAddedEvent::class => 'onAdded'
        ];
    }
    
    public function onAdded(UserAddedEvent $event): void
    {
        // Get user to notify
        $user = $event->getUser();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/auth/register.twig', [
            'user' => $user
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Création de votre compte');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
}


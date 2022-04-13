<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Auth\Event\UserAddedEvent;
use App\Domain\Auth\Event\UserIdentityAddedEvent;
use App\Infrastructure\Mailing\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerService $mailer
    )
    {
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            UserAddedEvent::class => 'onAdded',
            UserIdentityAddedEvent::class => 'onIdentityAdded'
        ];
    }
    
    public function onAdded(UserAddedEvent $event): void
    {
        // Get user to notify
        $user = $event->getUser();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/auth/register.twig', [
            'user' => $user,
            'signedUrl' => $event->getSignUrl()
        ]);
        $message->subject('🚗 AZ Agency – Confirmer votre adresse mail');
        $message->to($user->getEmail());
        $this->mailer->send($message);
        
        // Message document identity
        $message = $this->mailer->createEmail('mails/auth/identity_pending.twig', [
            'user' => $user
        ]);
        $message->subject('🚗 AZ Agency – Nous avons besoin de votre permis et document d’identité');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
    
    public function onIdentityAdded(UserIdentityAddedEvent $event): void
    {
        // Get user to notify
        $user = $event->getUser();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/auth/identity_added.twig', [
            'user' => $user
        ]);
        $message->subject('🚗 AZ Agency – Compte validé ✅');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
}


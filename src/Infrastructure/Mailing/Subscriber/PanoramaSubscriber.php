<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Order\Event\OrderValidatedEvent;
use App\Domain\Panorama\Event\PanoramaPendingEvent;
use App\Domain\Panorama\Event\PanoramaSendedEvent;
use App\Infrastructure\Mailing\MailerService;
use DataTables\Order;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PanoramaSubscriber implements EventSubscriberInterface
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
            PanoramaPendingEvent::class => 'onPending',
            PanoramaSendedEvent::class => 'onSended'
        ];
    }
    
    public function onPending(PanoramaPendingEvent $event): void
    {
        // Message verify email
        $message = $this->mailer->createEmail('mails/panorama/admin_pending.twig', [
            'panorama' => $event->getPanorama()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Un panorama est en attente de validation');
        $message->to($event->getReceiver()->getEmail());
        $this->mailer->send($message);
    }
    
    public function onSended(PanoramaSendedEvent $event): void
    {
        // Message verify email
        $message = $this->mailer->createEmail('mails/panorama/sended.twig', [
            'panorama' => $event->getPanorama()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Nouveau panorama');
        $message->to($event->getReceiver()->getEmail());
        $this->mailer->send($message);
    }
}


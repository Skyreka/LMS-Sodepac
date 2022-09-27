<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Order\Event\OrderValidatedEvent;
use App\Infrastructure\Mailing\MailerService;
use DataTables\Order;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderSubscriber implements EventSubscriberInterface
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
            OrderValidatedEvent::class => 'onValidated'
        ];
    }
    
    public function onValidated(OrderValidatedEvent $event): void
    {
        // Get user to notify
        $user = $event->getOrder()->getCustomer();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/order/validated.twig', [
            'order' => $event->getOrder()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Merci pour votre commande');
        $message->to($user->getEmail());
        $this->mailer->send($message);
        
        
        // Ware house message
        $message = $this->mailer->createEmail('mails/warehouse/new_order.twig', [
            'order' => $event->getOrder()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Nouvelle commande de '. $user->getIdentity());
        $message->to($user->getWarehouse()->getEmail());
        $this->mailer->send($message);
    
        if( $this->bag->get('second_order_email_notification') ) {
            // Message to second order
            $message = $this->mailer->createEmail('mails/warehouse/new_order.twig', [
                'order' => $event->getOrder()
            ]);
            $message->subject($this->bag->get('APP_NAME').' - Nouveau devis de '. $user->getIdentity());
            $message->to($this->bag->get('second_order_email_notification'));
            $this->mailer->send($message);
        }
    }
}


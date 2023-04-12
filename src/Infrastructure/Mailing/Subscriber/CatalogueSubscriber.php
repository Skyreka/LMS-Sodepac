<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Catalogue\Event\CatalogueValidatedEvent;
use App\Infrastructure\Mailing\MailerService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CatalogueSubscriber implements EventSubscriberInterface
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
            CatalogueValidatedEvent::class => 'onValidated'
        ];
    }

    public function onValidated(CatalogueValidatedEvent $event): void
    {
        $user = $event->getCatalogue()->getCustomer();

        // Message verify email
        $message = $this->mailer->createEmail('mails/catalogue/validated.twig', [
            'catalogue' => $event->getCatalogue()
        ]);

        $message->subject($this->bag->get('APP_NAME').' - Nouveau catalogue');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
}


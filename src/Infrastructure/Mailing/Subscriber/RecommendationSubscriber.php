<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Order\Event\OrderValidatedEvent;
use App\Domain\Recommendation\Event\RecommendationValidatedEvent;
use App\Domain\Signature\Event\OtpAddedEvent;
use App\Domain\Signature\Event\SignatureAskedEvent;
use App\Infrastructure\Mailing\MailerService;
use DataTables\Order;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecommendationSubscriber implements EventSubscriberInterface
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
            RecommendationValidatedEvent::class => 'onValidated'
        ];
    }
    
    public function onValidated(RecommendationValidatedEvent $event): void
    {
        $user = $event->getRecommendations()->getExploitation()->getUsers();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/recommendation/validated.twig', [
            'recommendation' => $event->getRecommendations()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Nouveau catalogue');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
}


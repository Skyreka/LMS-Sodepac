<?php

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Mime\Email;

class FailedMessageSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct ( MailerInterface $mailer )
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents ()
    {
        return [
            WorkerMessageFailedEvent::class => 'onMessageFailed'
        ];
    }

    public function onMessageFailed( WorkerMessageFailedEvent $event )
    {
        $message = get_class($event->getEnvelope()->getMessage());
        $trace = $event->getThrowable()->getTraceAsString();
        $email = ( new Email() )
            ->from('sys@sodepac.fr')
            ->subject('Erreur dans les taches asynchrones LMS-SODEPAC')
            ->to('sys@skyreka.com')
            //->addTo('contact@lmsdesign.fr')
            ->text('Une erreur est survenue lors du traitement d\'une tache <br> '. $message . '<br>' . $trace )
            ;
        $this->mailer->send( $email );
    }
}

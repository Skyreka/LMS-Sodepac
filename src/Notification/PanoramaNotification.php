<?php

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class PanoramaNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var
     */
    private $receiver;

    public function __construct( MailerInterface $mailer ) {
        $this->mailer = $mailer;
    }

    /**
     * @return \Exception|TransportExceptionInterface|void
     */
    public function sendNewPanorama() {
        $message = (new TemplatedEmail())
            ->subject('Un nouveau panorama disponible sur LMS-Sodepac.')
            ->from( new Address('noreply@sodepac.fr', 'LMS-Sodepac'))
            ->to( $this->getReceiver() )
            ->htmlTemplate( 'emails/notification/user/panorama.html.twig' )
            /*->setBody(
                $this->renderView(
                    'emails/notification/user/panorama.html.twig', [
                        'first_name' => $customer->getIdentity()
                    ]
                ),
                'text/html'
            )*/
        ;

        sleep(15 );

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e ) {
            return $e;
        }
    }


    /**
     * @return mixed
     */
    public function getReceiver(): string
    {
        return $this->receiver;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver): void
    {
        $this->receiver = $receiver;
    }
}

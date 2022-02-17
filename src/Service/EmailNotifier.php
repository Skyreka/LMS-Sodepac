<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use iio\libmergepdf\Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;

class EmailNotifier {

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(
        MailerInterface $mailer,
        EntityManagerInterface $em
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    /**
     * @return \Exception|TransportExceptionInterface|void
     */
    public function notify( int $userId, array $params ) {
        $user = $this->em->find( Users::class, $userId );

        $message = (new TemplatedEmail())
            ->subject( $params['subject'] )
            ->from( new Address('noreply@sodepac.fr', 'LMS-Sodepac'))
            ->to( $user->getEmail() )
            ->htmlTemplate( 'emails/notification/user/email_notification.html.twig' )
            ->context([
                'identity' => $user->getIdentity(),
                'text' => $params['text']
            ])
        ;

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e ) {
            return $e;
        }
    }
}

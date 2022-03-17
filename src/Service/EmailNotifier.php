<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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

        if ( !isset($params['title']) ) { $title = ''; } else { $title = $params['title']; }
        if ( !isset($params['text2']) ) { $text2 = ''; } else { $text2 = $params['text2']; }
        if ( !isset($params['text1']) ) { $text1 = ''; } else { $text1 = $params['text1']; }
        if ( !isset($params['link']) ) { $link = ''; } else { $link = $params['link']; }
        if ( !isset($params['btn_text']) ) { $btnText = ''; } else { $btnText = $params['btn_text']; }

        $message = (new TemplatedEmail())
            ->subject( $params['subject'] )
            ->from( new Address('noreply@sodepac.fr', ''))
            ->to( $user->getEmail() )
            ->htmlTemplate( 'emails/notification/user/email_notification.html.twig' )
            ->context([
                'identity' => $user->getIdentity(),
                'title' => $title,
                'text1' => $text1,
                'text2' => $text2,
                'link' => $link,
                'btn_text' => $btnText
            ])
        ;

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e ) {
            return $e;
        }
    }
}

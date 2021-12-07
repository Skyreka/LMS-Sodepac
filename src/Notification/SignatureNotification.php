<?php

namespace App\Notification;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;

class SignatureNotification {

    /**
     * @var MailerInterface
     */
    private $mail;

    /**
     * @var
     */
    private $receiver;

    public function __construct( \Swift_Mailer $mail ) {
        $this->mail = $mail;
    }

    public function sendAskSignMail(){
        // ENABLE SYSTEM
    }


    /**
     * @return mixed
     */
    public function getReceiver()
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

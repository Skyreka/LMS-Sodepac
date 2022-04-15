<?php

namespace App\Infrastructure\Mailing\Subscriber;

use App\Domain\Order\Event\OrderValidatedEvent;
use App\Domain\Signature\Event\OtpAddedEvent;
use App\Domain\Signature\Event\SignatureAskedEvent;
use App\Domain\Signature\Event\SignatureSignedEvent;
use App\Infrastructure\Mailing\MailerService;
use DataTables\Order;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SignatureSubscriber implements EventSubscriberInterface
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
            OtpAddedEvent::class => 'onOtpAdded',
            SignatureAskedEvent::class => 'onSignatureAsked',
            SignatureSignedEvent::class => 'onSigned'
        ];
    }
    
    public function onSignatureAsked(SignatureAskedEvent $event): void
    {
        $user = $event->getSignatureOtp()->getSignature()->getOrder()->getCustomer();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/signature/asked.twig', [
            'otp' => $event->getSignatureOtp()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Signature électronique de votre devis ');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
    
    public function onOtpAdded(OtpAddedEvent $event): void
    {
        $user = $event->getSignatureOtp()->getSignature()->getOrder()->getCustomer();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/signature/otp.twig', [
            'otp' => $event->getSignatureOtp()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Votre code OTP de signature électronique');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
    
    public function onSigned(SignatureSignedEvent $event): void
    {
        $user = $event->getSignature()->getOrder()->getCustomer();
        
        // Message verify email
        $message = $this->mailer->createEmail('mails/signature/signed.twig', [
            'signature' => $event->getSignature()
        ]);
        $message->subject($this->bag->get('APP_NAME').' - Confirmation de signature de votre commande');
        $message->to($user->getEmail());
        $this->mailer->send($message);
    }
}


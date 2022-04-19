<?php
declare(strict_types=1);

namespace App\Infrastructure\Mailing;

use App\Infrastructure\Queue\EnqueueMethod;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Crypto\DkimSigner;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Twig\Environment;

class MailerService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly EnqueueMethod $enqueue,
        private readonly MailerInterface $mailer,
        private readonly ?string $dkimKey = null,
        private readonly ?string $dkimSelector = null
    )
    {
    }
    
    public function createEmail(string $template, array $data = []): Email
    {
        $this->twig->addGlobal('format', 'html');
        $html = $this->twig->render($template, array_merge($data, ['layout' => 'mails/base.html.twig']));
        
        $this->twig->addGlobal('format', 'text');
        $text = $this->twig->render($template, array_merge($data, ['layout' => 'mails/base.text.twig']));
        
        return (new Email())
            ->from(new Address('ne-pas-repondre@sodepac.fr', 'LMS Sodepac'))
            ->html($html)
            ->text($text);
    }
    
    /**
     * Enqueue send basic email
     */
    public function send(Email $email): void
    {
        $this->enqueue->enqueue(self::class, 'sendNow', [$email]);
    }
    
    /**
     * Send email directly without enqueue
     */
    public function sendNow(Email $email): void
    {
        if($this->dkimKey) {
            $dkimSign = new DkimSigner("file://{$this->dkimKey}", 'sodepac.fr', $this->dkimSelector);
            // Sign manualy wait fix https://github.com/symfony/symfony/issues/40131
            $message = new Message($email->getPreparedHeaders(), $email->getBody());
            $email   = $dkimSign->sign($message);
        }
        $this->mailer->send($email);
    }
}

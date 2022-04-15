<?php

namespace App\Http\Controller;

use App\Domain\Order\Event\OrderValidatedEvent;
use App\Domain\Signature\Entity\Signature;
use App\Domain\Signature\Entity\SignatureOtp;
use App\Domain\Signature\Event\OtpAddedEvent;
use App\Domain\Signature\Form\SignatureSign;
use App\Domain\Signature\Repository\SignatureOtpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignatureController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SignatureOtpRepository $otpR,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
    }
    
    /**
     * @Route("/sign/order/{token}", name="signature_order_sign")
     */
    public function signOrder(
        Signature $signature,
        Request $request
    ): Response
    {
        $order = $signature->getOrder();
        $now   = new \DateTime();
        // Check Expiration
        if($signature->getAddedAt()->modify('+1 month') < $now && $signature->getSignAt() === null) {
            throw $this->createAccessDeniedException('Lien expiré');
        }
        // Check if doc is already signed
        if($signature->getSignAt() != null) {
            return $this->redirectToRoute('login');
        }
        
        $validOtp = false;
        // Check OTP
        foreach($signature->getOpts() as $otp) {
            if($otp->getExpiredAt() > $now) {
                $validOtp = true;
            }
        }
        
        // Create new OTP if no valid one have been found
        if($validOtp == false) {
            // Generate OTP
            $codeOtp = new SignatureOtp();
            $codeOtp->setSignature($signature);
            
            $this->dispatcher->dispatch(new OtpAddedEvent($codeOtp));
            
            // Save to DB
            $this->em->persist($codeOtp);
            $this->em->flush();
        }
        
        $form = $this->createForm(SignatureSign::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            // Check OTP
            $codeOtp = $this->otpR->findValidCodeBySignature($signature, $form->get('code')->getViewData());
            if($codeOtp != null) {
                // Save signature
                $signature->setIdentity($form->get('firstname')->getViewData() . ' ' . $form->get('lastname')->getViewData());
                $signature->setSignAt(new \DateTime());
                $signature->setUpdateAt(new \DateTime());
                $signature->setCodeOtp($codeOtp);
                
                // Disable Code
                $codeOtp->setIsActive(0);
                
                // Sign Order
                $order->setStatus(3);
                
                $this->dispatcher->dispatch(new OrderValidatedEvent($order));
                
                $this->em->flush();
                $this->addFlash('success', 'Commande signée avec succès');
                return $this->redirectToRoute('login_success', ['id_number' => $order->getIdNumber()]);
            } else {
                $this->addFlash('danger', 'Code non valide');
            }
            
            return $this->redirectToRoute('signature_order_sign', ['token' => $signature->getToken()]);
        }
        
        return $this->render('signature/sign_order.html.twig', [
            'order' => $signature->getOrder(),
            'form' => $form->createView()
        ]);
    }
}

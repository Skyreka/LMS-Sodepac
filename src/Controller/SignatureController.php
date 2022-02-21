<?php

namespace App\Controller;

use App\AsyncMethodService;
use App\Entity\Signature;
use App\Entity\SignatureOtp;
use App\Form\SignatureSign;
use App\Repository\OrdersRepository;
use App\Repository\SignatureOtpRepository;
use App\Service\EmailNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignatureController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var SignatureOTPRepository
     */
    private $otpR;

    public function __construct(
        EntityManagerInterface $em,
        SignatureOTPRepository $otpR
    )
    {
        $this->em = $em;
        $this->otpR = $otpR;
    }

    /**
     * @param Signature $signature
     * @param Request $request
     * @param AsyncMethodService $asyncMethodService
     * @param Mailer $mailer
     * @param OrdersRepository $or
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/sign/order/{token}", name="signature_order_sign")
     */
    public function signOrder(
        Signature $signature,
        Request $request,
        AsyncMethodService $asyncMethodService,
        Mailer $mailer,
        OrdersRepository $or
    ): Response
    {
        $order = $signature->getOrder();
        $now = new \DateTime();
        // Check Expiration
        if ( $signature->getAddedAt()->modify('+1 month') < $now && $signature->getSignAt() === null ) {
            throw $this->createAccessDeniedException('Lien expiré');
        }
        // Check if doc is already signed
        if ( $signature->getSignAt() != null ) {
            return $this->redirectToRoute( 'login' );
        }

        $validOtp = false;
        // Check OTP
        foreach ( $signature->getOpts() as $otp ) {
            if ( $otp->getExpiredAt() > $now ) {
                $validOtp = true;
            }
        }

        // Create new OTP if no valid one have been found
        if ( $validOtp == false ) {
            // Generate OTP
            $codeOtp = new SignatureOtp();
            $codeOtp->setSignature( $signature );

            // Send OTP
            $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $order->getCustomer()->getId(),
                'params' => [
                    'subject' => 'Votre code OTP de signature électronique - LMS-Sodepac',
                    'text' => 'Veuillez utiliser le code suivant pour valider votre signature:' .  $codeOtp->getCode() . ' <br> Ce code expire dans 1 mois'
                ]
            ]);

            // Save to DB
            $this->em->persist( $codeOtp );
            $this->em->flush();
        }

        $form = $this->createForm( SignatureSign::class );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            // Check OTP
            $codeOtp = $this->otpR->findValidCodeBySignature( $signature, $form->get('code')->getViewData() );
            if ( $codeOtp != null ) {
                // Save signature
                $signature->setIdentity( $form->get('firstname')->getViewData() . ' ' . $form->get('lastname')->getViewData() );
                $signature->setSignAt( new \DateTime() );
                $signature->setUpdateAt( new \DateTime() );
                $signature->setCodeOtp( $codeOtp );

                // Disable Code
                $codeOtp->setIsActive( 0 );

                // Sign Order
                $order->setStatus( 3 );

                // Send to Warehouse
                $message = (new TemplatedEmail())
                    ->subject( 'Nouvelle commande de ' . $order->getCreator()->getIdentity() )
                    ->from( new Address('noreply@sodepac.fr', 'LMS-Sodepac'))
                    ->to( $order->getCustomer()->getWarehouse()->getEmail() )
                    ->htmlTemplate( 'emails/notification/user/email_notification.html.twig' )
                    ->context([
                        'title' => 'Nouvelle commande venant de '. $order->getCreator()->getIdentity() .' ( CONFIDENTIEL ! )',
                        'text1' => 'Id de la commande #'. $order->getIdNumber(),
                        'text2' => 'Destinataire '. $order->getCustomer()->getIdentity(),
                        'link' => $this->generateUrl('order_pdf_view', ['id_number' => $order->getIdNumber(), 'print' => 'true'], UrlGeneratorInterface::ABSOLUTE_URL),
                        'btn_text' => 'Découvrir la commande'
                    ])
                ;
                try {
                    $mailer->send($message);
                } catch (TransportExceptionInterface $e ) {
                    return $e;
                }
                $this->em->flush();
                $this->addFlash('success', 'Commande signée avec succès');
                return $this->redirectToRoute( 'order_pdf_view', ['id_number' => $order->getIdNumber() ] );
            } else {
                $this->addFlash('danger', 'Code non valide');
            }

            return $this->redirectToRoute( 'signature_order_sign', ['token' => $signature->getToken() ] );
        }

        return $this->render('signature/sign_order.html.twig', [
            'order' => $signature->getOrder(),
            'form' => $form->createView()
        ]);
    }
}

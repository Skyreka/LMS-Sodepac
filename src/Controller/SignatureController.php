<?php

namespace App\Controller;

use App\Entity\Signature;
use App\Entity\SignatureOtp;
use App\Form\SignatureSign;
use App\Repository\OrdersRepository;
use App\Repository\SignatureOtpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param \Swift_Mailer $mailer
     * @return Response
     * @Route("/sign/order/{token}", name="signature_order_sign")
     */
    public function signOrder(
        Signature $signature,
        Request $request,
        \Swift_Mailer $mailer,
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
            $message = (new \Swift_Message('Votre code OTP - LMS SODEPAC'))
                ->setFrom('send@lms-manager.fr' )
                ->setTo( $order->getCustomer()->getEmail() )
                ->setBody(
                    $this->renderView(
                        'emails/signature/sign_otp.html.twig', [
                            'code_otp' => $codeOtp->getCode()
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);

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

                $this->em->flush();
                $this->addFlash('success', 'Contrat signé avec succès');
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

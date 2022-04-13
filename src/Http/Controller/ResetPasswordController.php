<?php

namespace App\Http\Controller;

use App\Domain\Auth\Users;
use App\Http\Form\ChangePasswordFormType;
use App\Http\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("reset-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;
    
    private $resetPasswordHelper;
    
    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }
    
    /**
     * Display & process form to request a password reset.
     * @Route("", name="app_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }
        
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }
    
    /**
     * Confirmation page after a user has requested a password reset.
     * @Route("check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
        // We prevent users from directly accessing this page
        if(! $this->canCheckEmail()) {
            return $this->redirectToRoute('app_forgot_password_request');
        }
        
        return $this->render('reset_password/check_email.html.twig', [
            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
        ]);
    }
    
    /**
     * Validates and process the reset URL that the user clicked in their email.
     * @Route("reset/{token}", name="app_reset_password")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        if($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);
            
            return $this->redirectToRoute('app_reset_password');
        }
        
        $token = $this->getTokenFromSession();
        if(null === $token) {
            throw $this->createNotFoundException('Aucun jeton de réinitialisation de mot de passe trouvé dans l\'URL ou dans la session.');
        }
        
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch(ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));
            
            return $this->redirectToRoute('app_forgot_password_request');
        }
        
        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);
            
            // Encode the plain password, and set it.
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            
            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();
            
            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();
            
            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
    
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy([
            'email' => $emailFormData,
        ]);
        
        // Marks that you are allowed to see the app_check_email page.
        $this->setCanCheckEmailInSession();
        
        // Do not reveal whether a user account was found or not.
        if(! $user) {
            return $this->redirectToRoute('app_check_email');
        }
        
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch(ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     'There was a problem handling your password reset request - %s',
            //     $e->getReason()
            // ));
            
            return $this->redirectToRoute('app_check_email');
        }
        
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@sodepac.fr', $this->getParameter('APP_NAME')))
            ->to($user->getEmail())
            ->subject('Votre demande de réinitialisation de mot de passe - ' . $this->getParameter('APP_NAME'))
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);
        
        $mailer->send($email);
        
        return $this->redirectToRoute('app_check_email');
    }
}
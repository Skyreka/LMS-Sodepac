<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\PasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends AbstractController {

    /**
     * @Route("/", name="login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param AuthorizationCheckerInterface $checker
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $checker ) {
        // Auto Redirect
        if ( !empty($this->getUser()) && $checker->isGranted('IS_AUTHENTICATED_REMEMBERED') && $this->getUser()->getPack() !== 'DISABLE' ) {
            return $this->redirectToRoute( 'login_success' );
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/login_success", name="login_success", methods={"GET"})
     */
    public function postLoginRedirection()
    {
        // Check connexion
        if ( empty($this->getUser())  ) {
            return $this->redirectToRoute('logout' );
        }
        //-- If user have pack disable redirect to login with error
        if ( $this->getUser()->getPack() === 'DISABLE' AND $this->getUser()->getStatus() == 'ROLE_USER' ) {
            $this->addFlash('danger', "Vous n'avez pas accès à l'application LMS Sodepac, veuillez contacter votre technicien Sodepac.");
            return $this->redirectToRoute( 'login' );
        }

        //-- Redirection User By Status
        switch ($this->getUser()->getStatus())
        {
            case 'ROLE_USER':
                return $this->redirectToRoute('home');
                break;
            case 'ROLE_TECHNICIAN':
                return $this->redirectToRoute('technician_home');
                break;
            case 'ROLE_ADMIN':
                return $this->redirectToRoute('admin_index');
                break;
            case 'ROLE_SALES':
                return $this->redirectToRoute('sales_manager_index');
                break;
            case 'ROLE_PRICING':
                return $this->redirectToRoute('pricing_index');
                break;
            case 'ROLE_SUPERADMIN':
                return $this->redirectToRoute('superadmin_index');
                break;
        }
    }

    /**
     * @Route("/active_user/{id}", name="security_active", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Users $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function active(Users $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(PasswordType::class, $user);
        $form->handleRequest($request);

        if ($user->getIsActive() === false OR $user->getIsActive() === NULL) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword( $encoder->encodePassword($user, $form['password']->getData()));
                $user->setIsActive( 1 );
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('login');
            }

            return $this->render('security/active.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('home');
    }

}

<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\PasswordType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/active_user/{id}", name="security.active")
     * @param Users $user
     * @param Request $request
     * @param ObjectManager $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function active(Users $user, Request $request, ObjectManager $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(PasswordType::class, $user);
        $form->handleRequest($request);

        if ($user->getIsActive() === false) {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword( $encoder->encodePassword($user, $form['password']->getData()));
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('home');
            }

            return $this->render('security/active.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('home');
    }

}
<?php
namespace App\Controller;

use App\Form\PasswordType;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class  AccountController extends AbstractController {

    /**
     * @var UsersRepository
     */
    private $repositoryUser;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UsersRepository $repository, EntityManagerInterface $em)
    {
        $this->repositoryUser = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/account", name="account")
     * @param Request $request
     * @return Response
     */
    public function account(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $form = $this->createForm( UserType::class, $user);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Informations éditées avec succès');
            return $this->redirectToRoute('login.success');
        }

        return $this->render('account/infos.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/password", name="account.password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $em
     * @return Response
     */
    public function password(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $form = $this->createForm( PasswordType::class, $user);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword( $encoder->encodePassword($user, $form['password']->getData()));
            $user->setReset(0);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('login.success');
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
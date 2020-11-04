<?php
namespace App\Controller;

use App\Form\PasswordType;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/account")
 */
class  AccountController extends AbstractController {

    /**
     * @var UsersRepository
     */
    private $repositoryUser;

    private $em;

    public function __construct(UsersRepository $repository, EntityManagerInterface $em)
    {
        $this->repositoryUser = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="account_index", methods={"GET", "POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        // User information
        $form = $this->createForm( UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Informations éditées avec succès');
            return $this->redirectToRoute('account_index');
        }

        // User password
        $formPassword = $this->createForm( PasswordType::class, $user);
        $formPassword->handleRequest( $request );

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $user->setPassword( $encoder->encodePassword($user, $formPassword['password']->getData()));
            // Disable reset of technician edit pass of user
            $user->setReset( 0 );
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
            'form_password' => $formPassword->createView()
        ]);
    }
}

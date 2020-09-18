<?php
namespace App\Controller;

use App\Entity\Exploitation;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUsersController extends AbstractController {

    /**
     * @var UsersRepository
     */
    private $repositoryUsers;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UsersRepository $repositoryUsers, EntityManagerInterface $em)
    {
        $this->repositoryUsers = $repositoryUsers;
        $this->em = $em;
    }

    /**
     * @Route("/admin/users", name="admin.users.index")
     */
    public function index(): Response
    {
        $users = $this->repositoryUsers->findAllUsersAndTechnician();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/users/show/{id}", name="admin.users.show")
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $user = $this->repositoryUsers->find( $id );
        return $this->render('admin/users/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/users/edit/{id}", name="admin.users.edit", methods="GET|POST")
     * @param Users $user
     * @param Request $request
     * @return Response
     */
    public function edit(Users $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('danger', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/new", name="admin.users.new")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function new(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $user->setPassword( $encoder->encodePassword($user, '0000'));
            $user->setStatus('ROLE_USER');
            $user->setIsActive(1);
            $this->em->flush();

            //Send Email to user
            $link = $request->getUriForPath('/login');
            $message = (new \Swift_Message('Votre compte LMS Sodepac est maintenant disponible.'))
                ->setFrom('send@lms-sodepac.fr')
                ->setTo( $user->getEmail() )
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig', [
                            'first_name' => $user->getFirstname(),
                            'link' => $link
                        ]
                    ),
                    'text/html'
                )
            ;
            $mail = $mailer->send($message);
            $this->addFlash('success', 'Utilisateur crée avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin.users.delete", methods="DELETE")
     * @param Users $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Users $user, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token' ))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }
        return $this->redirectToRoute('admin.users.index');
    }

    /**
     * @Route("/admin/users/edit/exploitation/{id}", name="admin.users.edit.exploitation")
     * @param Exploitation $exploitation
     * @param Request $request
     * @return Response
     */
    public function editExploitation(Exploitation $exploitation, Request $request): Response
    {
        $form = $this->createForm(ExploitationType::class, $exploitation);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Check if user selected have demo pack only 10 ha max
            if ( $form->getData()->getUsers()->getPack() === 'PACK_DEMO' && $form->getData()->getSize() > 10 ) {
                $this->addFlash('error', "L'utilisateur a un pack Démo il ne peut pas avoir une exploitation de plus de 10ha");
            } else {
                $this->em->persist( $exploitation );
                $this->em->flush();
                $this->addFlash('success', 'Exploitation modifiée avec succès');
                return $this->redirectToRoute( 'admin.users.index' );
            }
        }

        return $this->render('technician/customers/size.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/users/password/{id}", name="admin.users.password")
     * @param Users $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function password(Users $user, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm( PasswordType::class, $user);
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $user->setPassword( $encoder->encodePassword($user, $form['password']->getData()));
            $user->setReset(1);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe du client modifié avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/password.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
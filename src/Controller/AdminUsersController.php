<?php
namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function __construct(UsersRepository $repositoryUsers, ObjectManager $em)
    {
        $this->repositoryUsers = $repositoryUsers;
        $this->em = $em;
    }

    /**
     * @Route("/admin/users", name="admin.users.index")
     */
    public function index(): Response
    {
        $users = $this->repositoryUsers->findAllByRole('ROLE_USER');
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
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            //Send Email to user
            $link = 'http://127.0.0.1:8000/active_user/'.$user->getId();
            $message = (new \Swift_Message('Votre compte LMS Sodepac est maintenant disponible.'))
                ->setFrom('send@example.com')
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
            $mailer->send($message);

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
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }
        return $this->redirectToRoute('admin.users.index');
    }
}
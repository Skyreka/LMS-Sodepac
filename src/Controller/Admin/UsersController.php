<?php
namespace App\Controller\Admin;

use App\Entity\Exploitation;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
use App\Form\UserType;
use App\Repository\RecommendationProductsRepository;
use App\Repository\UsersRepository;
use DataTables\DataTablesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UsersController
 * @package App\Controller
 * @Route("/admin/users")
 */
class UsersController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin_users_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }

    /**
     * @Route("/data", name="admin_users_data", methods={"GET"})
     * @param Request $request
     * @param DataTablesInterface $datatables
     * @return JsonResponse
     */
    public function data(Request $request, DataTablesInterface $datatables): JsonResponse
    {
        try {
            $results = $datatables->handle($request, 'users');

            return $this->json($results);
        }
        catch (HttpException $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        }
    }

    /**
     * @Route("/edit/{id}", name="admin_users_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Users $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function edit(Users $user, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // Edit information user
        $form = $this->createForm(UserType::class, $user);
        if ($user->getStatus() === 'ROLE_TECHNICIAN') {
            $form = $this->createForm(UserType::class, $user, ['is_technician' => true]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users_edit', ['id' => $user->getId()]);
        }

        // Edit Password User
        $formPassword = $this->createForm( PasswordType::class, $user);
        $formPassword->handleRequest( $request );

        if ( $formPassword->isSubmitted() && $formPassword->isValid() ) {
            $user->setPassword( $encoder->encodePassword($user, $formPassword['password']->getData()));
            $user->setReset(1);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('admin_users_edit', ['id' => $user->getId()]);
        }

        // Edit Exploitation User
        $formExploitation = $this->createForm( ExploitationType::class, $user->getExploitation());
        $formExploitation->handleRequest( $request );

        if ( $formExploitation->isSubmitted() && $formExploitation->isValid() ) {
            $this->em->flush();
            $this->addFlash('success', 'Exploitation mis à jour avec succès');
            return $this->redirectToRoute('admin_users_edit', ['id' => $user->getId()]);
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'form_password' => $formPassword->createView(),
            'form_exploitation' => $formExploitation->createView()
        ]);
    }

    /**
     * @Route("/new", name="admin_users_new", methods={"GET", "POST"})
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
            $mailer->send($message);
            $this->addFlash('success', 'Utilisateur crée avec succès');
            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete", methods="DELETE", requirements={"id":"\d+"})
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
        return $this->redirectToRoute('admin_users_index');
    }

    /**
     * @Route("/exploitation_new/{id}", name="admin_users_exploitation_new", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Users $user
     * @param Request $request
     * @return Response
     */
    public function exploitationNew(Users $user, Request $request): Response
    {
        $exploitation = $this->getUser()->getExploitation();
        if ($exploitation) {
            throw $this->createNotFoundException('Exploitation déjà crée pour cet utilisateur.');
        }

        $exploitation = new Exploitation();
        $form = $this->createForm(ExploitationType::class, $exploitation);
        $form->handleRequest( $request );

        $exploitation->setUsers( $user );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist( $exploitation );
            $this->em->flush();
            $this->addFlash('success', 'Ajout d\'une exploitation avec succès');
            return $this->redirectToRoute( 'admin_users_edit', ['id' => $user->getId()] );
        }

        return $this->render('admin/users/exploitation.html.twig', [
            'form_exploitation' => $form->createView()
        ]);
    }

}

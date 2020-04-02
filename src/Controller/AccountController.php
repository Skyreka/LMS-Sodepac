<?php
namespace App\Controller;

use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController {

    /**
     * @var UsersRepository
     */
    private $repositoryUser;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UsersRepository $repository, ObjectManager $em)
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

        return $this->render('pages/infos.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
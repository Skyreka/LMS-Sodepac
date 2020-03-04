<?php
namespace App\Controller;

use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $users = $this->repositoryUsers->findAll();
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
     * @Route("/admin/users/edit/{id}", name="admin.users.edit")
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
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
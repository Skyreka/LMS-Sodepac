<?php

namespace App\Controller;

use App\Entity\Cultures;
use App\Entity\Exploitation;
use App\Entity\Ilots;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\TechnicianCustomersType;
use App\Form\UserType;
use App\Repository\AnalyseRepository;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\InterventionsRepository;
use App\Repository\IrrigationRepository;
use App\Repository\StocksRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Zumba\JsonSerializer\JsonSerializer;

class TechnicianCustomersController extends AbstractController
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UsersRepository $usersRepository, EntityManagerInterface $em)
    {
        $this->usersRepository = $usersRepository;
        $this->em = $em;
    }

    /**
     * @Route("technician/customers", name="technician.customers.index")
     */
    public function index(): Response
    {
        $customers = $this->usersRepository->findAllCustomersOfTechnician( $this->getUser()->getId() );
        return $this->render('technician/customers/index.html.twig', [
            'customers' => $customers
        ]);
    }

    /**
     * @Route("technician/customers/new", name="technician.customers.new")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function new( Request $request, \Swift_Mailer $mailer)
    {
        $user = new Users();
        $form = $this->createForm( TechnicianCustomersType::class, $user );
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //: Setters
            $user->setTechnician( $this->getUser()->getId() );
            $user->setStatus( 'ROLE_USER' );

            //: Update
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

            $this->addFlash('success', 'Nouveau client crée avec succès');

            return $this->redirectToRoute('technician.customers.index');
        }

        return $this->render('technician/customers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/edit/{id}", name="technician.customers.edit")
     * @param Users $user
     * @param Request $request
     * @return Response
     */
    public function edit(Users $user, Request $request): Response
    {
        $form = $this->createForm( TechnicianCustomersType::class, $user);
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->flush();
            $this->addFlash('success', 'Client modifié avec succès');
            return $this->redirectToRoute('technician.customers.index');
        }

        return $this->render('technician/customers/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/new/exploitation/{id}", name="technician.customers.new.exploitation")
     * @param Users $user
     * @param Request $request
     * @return Response
     */
    public function newExploitation(Users $user, Request $request): Response
    {
        $exploitation = new Exploitation();
        $form = $this->createForm(ExploitationType::class, $exploitation);
        $form->handleRequest( $request );

        $exploitation->setUsers( $user );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist( $exploitation );
            $this->em->flush();
            $this->addFlash('success', 'Ajout d\'une exploitation avec succès');
            return $this->redirectToRoute( 'technician.customers.index' );
        }

        return $this->render('technician/customers/exploitation.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
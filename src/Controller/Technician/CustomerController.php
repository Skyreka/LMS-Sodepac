<?php

namespace App\Controller\Technician;

use App\Entity\Cultures;
use App\Entity\Exploitation;
use App\Entity\Ilots;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Zumba\JsonSerializer\JsonSerializer;

/**
 * Class CustomersController
 * @package App\Controller\Technician
 * @Route("/technician/customers")
 */
class CustomerController extends AbstractController
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
     * @Route("/", name="technician_customers_index", methods={"GET"})
     */
    public function index(): Response
    {
        $customers = $this->usersRepository->findAllCustomersOfTechnician( $this->getUser()->getId() );
        return $this->render('technician/customers/index.html.twig', [
            'customers' => $customers
        ]);
    }

    /**
     * @Route("/new", name="technician_customers_new", methods={"GET", "POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function new( Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();
        $form = $this->createForm( TechnicianCustomersType::class, $user );
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //: Setters
            $user->setTechnician( $this->getUser() );
            $user->setPassword( $encoder->encodePassword($user, '0000'));
            $user->setIsActive(1);
            $user->setStatus( 'ROLE_USER' );

            //: Update
            $this->em->persist($user);
            $this->em->flush();

            //Send Email to user
            $link = $request->getUriForPath('/login');
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

            return $this->redirectToRoute('technician_customers_index');
        }

        return $this->render('technician/customers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/edit/{id}", name="technician_customers_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
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
            return $this->redirectToRoute('technician_customers_index');
        }

        return $this->render('technician/customers/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/password/{id}", name="technician_customers_password", methods={"GET", "POST"}, requirements={"id":"\d+"})
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
            return $this->redirectToRoute('technician_customers_index');
        }

        return $this->render('technician/customers/password.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/new/exploitation/{id}", name="technician_customers_new_exploitation", methods={"GET", "POST"}, requirements={"id":"\d+"})
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
            return $this->redirectToRoute( 'technician_customers_index' );
        }

        return $this->render('technician/customers/exploitation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("technician/customers/edit/exploitation/{id}", name="technician_customers_edit_exploitation", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Exploitation $exploitation
     * @param Request $request
     * @return Response
     */
    public function editExploitation(Exploitation $exploitation, Request $request): Response
    {
        $form = $this->createForm(ExploitationType::class, $exploitation);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist( $exploitation );
            $this->em->flush();
            $this->addFlash('success', 'Exploitation modifiée avec succès');
            return $this->redirectToRoute( 'technician_customers_index' );
        }

        return $this->render('technician/customers/size.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

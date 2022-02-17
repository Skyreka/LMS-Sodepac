<?php

namespace App\Controller\Technician;

use App\AsyncMethodService;
use App\Entity\Exploitation;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
use App\Form\TechnicianCustomersType;
use App\Repository\UsersRepository;
use App\Service\EmailNotifier;
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
     * @param UserPasswordEncoderInterface $encoder
     * @param AsyncMethodService $asyncMethodService
     * @return Response
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        AsyncMethodService $asyncMethodService
    )
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

            //: Create exploitation
            $exploitation = new Exploitation();
            $exploitation
                ->setSize(300)
                ->setUsers($user);

            //: Update
            $this->em->persist($user);
            $this->em->persist($exploitation);
            $this->em->flush();

            //Send Email to user
            $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $user->getId(),
                'params' => [
                    'subject' => 'Votre compte LMS Sodepac est maintenant disponible.',
                    'text1' => 'Votre compte LMS Sodepac est maintenant disponible.'
                ]
            ]);

            $this->addFlash('success', 'Nouveau client crée avec succès');

            return $this->redirectToRoute('technician_customers_index');
        }

        return $this->render('technician/customers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

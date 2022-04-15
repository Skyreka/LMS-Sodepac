<?php

namespace App\Http\Controller\Technician;

use App\Domain\Auth\Event\UserAddedEvent;
use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Auth\Users;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Http\Form\TechnicianCustomersType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zumba\JsonSerializer\JsonSerializer;

/**
 * Class CustomersController
 * @package App\Controller\Technician
 * @Route("/technician/customers")
 */
class CustomerController extends AbstractController
{
    public function __construct(
        private readonly UsersRepository $usersRepository,
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
    }
    
    /**
     * @Route("/", name="technician_customers_index", methods={"GET"})
     */
    public function index(): Response
    {
        $customers = $this->usersRepository->findAllCustomersOfTechnician($this->getUser()->getId());
        return $this->render('technician/customers/index.html.twig', [
            'customers' => $customers
        ]);
    }
    
    /**
     * @Route("/new", name="technician_customers_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $encoder
    )
    {
        $user = new Users();
        $form = $this->createForm(TechnicianCustomersType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //: Setters
            $user->setTechnician($this->getUser());
            $user->setPassword($encoder->encodePassword($user, '0000'));
            $user->setIsActive(1);
            $user->setStatus('ROLE_USER');
            
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
            $this->dispatcher->dispatch( new UserAddedEvent($user));
            
            $this->addFlash('success', 'Nouveau client crée avec succès');
            
            return $this->redirectToRoute('technician_customers_index');
        }
        
        return $this->render('technician/customers/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

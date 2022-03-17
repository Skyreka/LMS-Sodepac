<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\TicketsMessages;
use App\Form\TicketsNewMessageType;
use App\Form\TicketsNewType;
use App\Repository\TicketsMessagesRepository;
use App\Repository\TicketsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TicketsController
 * @package App\Controller
 * @Route("tickets")
 */
class TicketsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * StockController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("", name="tickets_index", methods={"GET"})
     * @param TicketsRepository $tr
     * @return Response
     */
    public function index( TicketsRepository $tr)
    {
        if ( $this->getUser()->getRoles() == ['ROLE_TECHNICIAN']) {
            $tickets = $tr->findAllByTechnician($this->getUser());
        } else {
            $tickets = $tr->findAllByUser($this->getUser());
        }
        return $this->render('tickets/index.html.twig', [
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("new", name="tickets_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new( Request $request ): Response
    {
        $tickets = new Tickets();
        $form = $this->createForm(TicketsNewType::class, $tickets);
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $tickets->setTechnician( $this->getUser()->getTechnician());
            $tickets->setUser( $this->getUser() );
            $this->em->persist( $tickets );
            $this->em->flush();
            $this->addFlash('success', 'Ticket crée avec succès');
            return $this->redirectToRoute('tickets_conversation_show', ['id' => $tickets->getId() ]);
        }

        return $this->render('tickets/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("conversation/{id}", name="tickets_conversation_show", methods={"POST", "GET"}, requirements={"id":"\d+"})
     * @param Tickets $ticket
     * @param TicketsMessagesRepository $tmr
     * @param TicketsRepository $tr
     * @param Request $request
     * @return Response
     */
    public function conversationShow( Tickets $ticket, TicketsMessagesRepository $tmr, TicketsRepository $tr, Request $request )
    {
        // security check
        if ( $this->getUser() != $ticket->getUser() && $this->getUser() != $ticket->getTechnician() ) {
            return $this->redirectToRoute('login_success');
        }
        // get message of ticket
        $messages = $tmr->findBy( ['ticket' => $ticket]);
        // list of tickets on left
        if ( $this->getUser()->getRoles() == ['ROLE_TECHNICIAN']) {
            $ticketsList = $tr->findAllByTechnician($this->getUser());
        } else {
            $ticketsList = $tr->findAllByUser($this->getUser());
        }
        // create form send message
        $ticketsMessages = new TicketsMessages();
        $form = $this->createForm(TicketsNewMessageType::class, $ticketsMessages);
        $form->handleRequest( $request );

        // send message
        if ($form->isSubmitted() && $form->isValid()) {
            $ticketsMessages->setFromId( $this->getUser() );
            $ticketsMessages->setTicket( $ticket );
            // file
            $file = $form->get('file')->getData();
            if ($file) {
                $originalFileName = pathinfo( $file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalFileName . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('chat_message_directory'),
                        $newFileName
                    );
                } catch ( FileException $e ) {
                    echo $e->getMessage(); die();
                }
                $ticketsMessages->setFile( $newFileName );
            }
            $this->em->persist( $ticketsMessages );
            $this->em->flush();
            return $this->redirectToRoute('tickets_conversation_show', ['id' => $ticket->getId()]);
        }

        return $this->render('tickets/conversation.html.twig', [
            'ticket' => $ticket,
            'ticketsList' => $ticketsList,
            'messages' => $messages,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("close/{id}", name="tickets_close", methods="CLOSE", requirements={"id":"\d+"})
     * @param Tickets $tickets
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function check(Tickets $tickets, Request $request)
    {
        if ($this->isCsrfTokenValid('close' . $tickets->getId(), $request->get('_token'))) {
            $tickets->setStatus(0);
            $datetime = New \DateTime();
            $tickets->setClosedAt($datetime);
            $this->em->flush();
        }

        return $this->redirectToRoute('tickets_index');
    }
}

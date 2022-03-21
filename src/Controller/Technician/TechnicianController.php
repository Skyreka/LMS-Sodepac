<?php
namespace App\Controller\Technician;

use App\Repository\BsvUsersRepository;
use App\Repository\OrdersRepository;
use App\Repository\PanoramaRepository;
use App\Repository\PurchaseContractRepository;
use App\Repository\RecommendationsRepository;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnicianController extends AbstractController {

    /**
     * @Route("technician", name="technician_home")
     * @param UsersRepository $ur
     * @param PanoramaRepository $pr
     * @param BsvUsersRepository $bur
     * @param TicketsRepository $tr
     * @param RecommendationsRepository $rr
     * @param OrdersRepository $or
     * @param PurchaseContractRepository $pcr
     * @return Response
     */
    public function home( UsersRepository $ur, PanoramaRepository $pr, BsvUsersRepository $bur, TicketsRepository $tr, RecommendationsRepository $rr, OrdersRepository $or, PurchaseContractRepository $pcr)
    {
        $customers = $ur->findAllCustomersOfTechnician( $this->getUser()->getId(), 10 );
        $customersCount = count($ur->findAllCustomersOfTechnician( $this->getUser()->getId() ));
        $panoramas = $pr->findAllNotDeleted(3);
        $flashs = $bur->findAllByTechnician($this->getUser()->getId(), 3);
        $tickets = $tr->findAllByTechnician($this->getUser(), 3);
        $recommendations = $rr->findByExploitationOfTechnician($this->getUser(), 3);
        $orders = $or->findByTechnician($this->getUser(), 3);
        $contracts = $pcr->findBy( ['creator' => $this->getUser() ], ['added_date' => 'DESC'], 3);

        return $this->render('technician/home.html.twig', [
            'customers' => $customers,
            'panoramas' => $panoramas,
            'flashs' => $flashs,
            'customersCount' => $customersCount,
            'tickets' => $tickets,
            'recommendations' => $recommendations,
            'orders' => $orders,
            'contracts' => $contracts
        ]);
    }

}

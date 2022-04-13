<?php

namespace App\Http\Controller\Technician;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Bsv\Repository\BsvUsersRepository;
use App\Domain\Order\Repository\OrdersRepository;
use App\Domain\Panorama\Repository\PanoramaRepository;
use App\Domain\Purchase\Repository\PurchaseContractRepository;
use App\Domain\Recommendation\Repository\RecommendationsRepository;
use App\Domain\Ticket\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnicianController extends AbstractController
{
    
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
    public function home(UsersRepository $ur, PanoramaRepository $pr, BsvUsersRepository $bur, TicketsRepository $tr, RecommendationsRepository $rr, OrdersRepository $or, PurchaseContractRepository $pcr)
    {
        $customers       = $ur->findAllCustomersOfTechnician($this->getUser()->getId(), 10);
        $customersCount  = count($ur->findAllCustomersOfTechnician($this->getUser()->getId()));
        $panoramas       = $pr->findAllNotDeleted(3);
        $flashs          = $bur->findAllByTechnician($this->getUser()->getId(), 3);
        $tickets         = $tr->findAllByTechnician($this->getUser(), 3);
        $recommendations = $rr->findByExploitationOfTechnician($this->getUser(), 3);
        $orders          = $or->findByTechnician($this->getUser(), 3);
        $contracts       = $pcr->findBy(['creator' => $this->getUser()], ['added_date' => 'DESC'], 3);
        
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

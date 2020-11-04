<?php
namespace App\Controller;

use App\Repository\BsvUsersRepository;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\InterventionsRepository;
use App\Repository\PanoramasRepository;
use App\Repository\PanoramaUserRepository;
use App\Repository\RecommendationProductsRepository;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/technician", name="technician.home")
     * @param UsersRepository $ur
     * @param PanoramasRepository $pr
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function homeTechnicians( UsersRepository $ur, PanoramasRepository $pr, BsvUsersRepository $bur)
    {
        $customers = $ur->findAllCustomersOfTechnician( $this->getUser()->getId(), 10 );
        $customersCount = count($ur->findAllCustomersOfTechnician( $this->getUser()->getId() ));
        $panoramas = $pr->findAllNotDeleted(3);
        $bsvs = $bur->findAllByTechnician($this->getUser()->getId(), 3);
        return $this->render('technician/home.html.twig', [
            'customers' => $customers,
            'panoramas' => $panoramas,
            'bsvs' => $bsvs,
            'customersCount' => $customersCount
        ]);
    }

    /**
     * @Route("/home", name="home")
     * @param IlotsRepository $ir
     * @param BsvUsersRepository $bur
     * @param PanoramaUserRepository $pur
     * @param TicketsRepository $tr
     * @return Response
     * @throws \Exception
     */
    public function homeUsers(IlotsRepository $ir, BsvUsersRepository $bur, PanoramaUserRepository $pur, TicketsRepository $tr): Response
    {
        $ilots = $ir->findIlotsFromUser( $this->getUser()->getExploitation() );
        $bsvs = $bur->findAllByCustomer($this->getUser(), 3);
        $panoramas = $pur->findAllByCustomer($this->getUser(), 3);
        $tickets = $tr->findAllByUser( $this->getUser(), 3);

        //-- Clear listCulture
        $this->container->get('session')->remove('listCulture');

        return $this->render('pages/home.html.twig', [
            'bsvs' => $bsvs,
            'panoramas' => $panoramas,
            'ilots' => $ilots,
            'tickets' => $tickets
        ]);
    }

}

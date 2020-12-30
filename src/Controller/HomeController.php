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
     * @Route("/home", name="home")
     * @param IlotsRepository $ir
     * @param BsvUsersRepository $bur
     * @param PanoramaUserRepository $pur
     * @param TicketsRepository $tr
     * @return Response
     * @throws \Exception
     */
    public function home(IlotsRepository $ir, BsvUsersRepository $bur, PanoramaUserRepository $pur, TicketsRepository $tr): Response
    {
        $ilots = $ir->findIlotsFromUser( $this->getUser()->getExploitation() );
        $flashs = $bur->findAllByCustomer($this->getUser(), 3);
        $panoramas = $pur->findAllByCustomer($this->getUser(), 3);
        $tickets = $tr->findAllByUser( $this->getUser(), 3);

        //-- Clear listCulture
        $this->container->get('session')->remove('listCulture');

        return $this->render('pages/home.html.twig', [
            'flashs' => $flashs,
            'panoramas' => $panoramas,
            'ilots' => $ilots,
            'tickets' => $tickets
        ]);
    }

}

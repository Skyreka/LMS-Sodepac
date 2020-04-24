<?php
namespace App\Controller;

use App\Repository\BsvUsersRepository;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\PanoramasRepository;
use App\Repository\PanoramaUserRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/technician", name="technician.home")
     * @param UsersRepository $ur
     * @param PanoramasRepository $pr
     * @return Response
     */
    public function homeTechnicians( UsersRepository $ur, PanoramasRepository $pr)
    {
        $customers = $ur->findAllCustomersOfTechnician( $this->getUser()->getId(), 10 );
        $panoramas = $pr->findAllNotDeleted(3);
        return $this->render('technician/home.html.twig', [
            'customers' => $customers,
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/home", name="home")
     * @param IlotsRepository $ir
     * @param UsersRepository $ur
     * @param CulturesRepository $cr
     * @param BsvUsersRepository $bur
     * @param PanoramaUserRepository $pur
     * @return Response
     */
    public function homeUsers(IlotsRepository $ir, UsersRepository $ur, CulturesRepository $cr, BsvUsersRepository $bur, PanoramaUserRepository $pur): Response
    {
        $ilots = $ir->findIlotsFromUser( $this->getUser()->getExploitation() );
        $bsvs = $bur->findAllByCustomer($this->getUser(), 3);
        $panoramas = $pur->findAllByCustomer($this->getUser(), 3);

        return $this->render('pages/home.html.twig', [
            'bsvs' => $bsvs,
            'panoramas' => $panoramas,
            'ilots' => $ilots
        ]);
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return $this->render('pages/index.html.twig');
    }
}
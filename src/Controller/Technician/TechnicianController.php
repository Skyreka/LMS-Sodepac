<?php
namespace App\Controller\Technician;

use App\Repository\BsvUsersRepository;
use App\Repository\PanoramasRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechnicianController extends AbstractController {

    /**
     * @Route("/technician", name="technician_home")
     * @param UsersRepository $ur
     * @param PanoramasRepository $pr
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function home( UsersRepository $ur, PanoramasRepository $pr, BsvUsersRepository $bur)
    {
        $customers = $ur->findAllCustomersOfTechnician( $this->getUser()->getId(), 10 );
        $customersCount = count($ur->findAllCustomersOfTechnician( $this->getUser()->getId() ));
        $panoramas = $pr->findAllNotDeleted(3);
        $flashs = $bur->findAllByTechnician($this->getUser()->getId(), 3);
        return $this->render('technician/home.html.twig', [
            'customers' => $customers,
            'panoramas' => $panoramas,
            'flashs' => $flashs,
            'customersCount' => $customersCount
        ]);
    }

}

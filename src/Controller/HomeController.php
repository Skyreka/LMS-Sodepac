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
        $panoramas = $pr->findAllNotDeleted(3);
        $bsvs = $bur->findAllByTechnician($this->getUser()->getId(), 3);
        return $this->render('technician/home.html.twig', [
            'customers' => $customers,
            'panoramas' => $panoramas,
            'bsvs' => $bsvs
        ]);
    }

    /**
     * @Route("/admin", name="admin.home")
     * @param UsersRepository $ur
     * @param RecommendationProductsRepository $rpr
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function homeAdmins( UsersRepository $ur, RecommendationProductsRepository $rpr)
    {
        $customers = $ur->findAllByRole('ROLE_USER');
        $customersCount = count($customers);

        $inactiv = $ur->findAllByPack('DISABLE');
        $inactivCount = count($inactiv);
        $inactivPercent = 100 * $inactivCount / $customersCount;

        $full = $ur->findAllByPack('PACK_FULL');
        $fullCount = count($full);
        $fullPercent = 100 * $fullCount / $customersCount;

        $light = $ur->findAllByPack('PACK_LIGHT');
        $lightCount = count($light);
        $lightPercent = 100 * $lightCount / $customersCount;

        $demo = $ur->findAllByPack('PACK_DEMO');
        $demoCount = count($demo);
        $demoPercent = 100 * $demoCount / $customersCount;

        $totalLitre = $rpr->findQuantityUsedByUnit('L', 'L/Ha');
        $totalKilo = $rpr->findQuantityUsedByUnit('Kg', 'Kg/Ha');

        return $this->render('admin/home.html.twig', [
            'inactivCount' => $inactivCount,
            'inactivPercent' => $inactivPercent,
            'fullCount' => $fullCount,
            'fullPercent' => $fullPercent,
            'lightCount' => $lightCount,
            'lightPercent' => $lightPercent,
            'demoCount' => $demoCount,
            'demoPercent' => $demoPercent,
            'totalLitre' => $totalLitre[1],
            'totalKilo' => $totalKilo[1]
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
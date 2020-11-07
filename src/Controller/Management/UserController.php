<?php

namespace App\Controller\Management;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Entity\Users;
use App\Repository\AnalyseRepository;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\InterventionsRepository;
use App\Repository\IrrigationRepository;
use App\Repository\StocksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Management
 * @Route("/management/user")
 */
class UserController extends AbstractController
{
    /**
     * View information of user for tech and admin
     * @Route("/{id}", name="management_user_show", methods={"GET"}, requirements={"id":"\d+"})
     * @param Users $customer
     * @param StocksRepository $sr
     * @param IlotsRepository $ir
     * @param IrrigationRepository $irrigationRepo
     * @param AnalyseRepository $analyseRepo
     * @return Response
     */
    public function show(Users $customer, StocksRepository $sr, IlotsRepository $ir, IrrigationRepository $irrigationRepo, AnalyseRepository $analyseRepo): Response
    {
        // Security for technican can't view customer of other technican
        if ( $this->getUser()->getStatus() == 'ROLE_TECHNICIAN' AND $customer->getTechnician() != $this->getUser() ) {
            throw $this->createNotFoundException('Cette utilisateur ne vous appartient pas.');
        }

        $exploitationOfCustomer = $customer->getExploitation();
        $usedProducts = $sr->findByExploitation( $exploitationOfCustomer, true );
        $ilots = $ir->findBy( ['exploitation' => $exploitationOfCustomer], null, '7' );
        $irrigations = $irrigationRepo->findByExploitation( $exploitationOfCustomer, 7 );
        $analyses = $analyseRepo->findByExploitation( $exploitationOfCustomer, 7 );
        return $this->render('management/user/show.html.twig', [
            'customer' => $customer,
            'usedProducts' => $usedProducts,
            'ilots' => $ilots,
            'irrigations' => $irrigations,
            'analyses' => $analyses
        ]);
    }

    /**
     * @Route("/ilot/{id}", name="management_user_ilot_show", methods={"GET"}, requirements={"id":"\d+"})
     * @param Ilots $ilot
     * @param CulturesRepository $cr
     * @return Response
     */
    public function showIlots(Ilots $ilot, CulturesRepository $cr): Response
    {
        $customer = $ilot->getExploitation()->getUsers();
        $cultures = $cr->findBy( ['ilot' => $ilot] );

        return $this->render('management/user/ilot.html.twig', [
            'customer' => $customer,
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("/culture/{id}", name="management_user_culture_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Cultures $culture
     * @param InterventionsRepository $interventionsRepository
     * @return Response
     */
    public function showCulture(Cultures $culture, InterventionsRepository $interventionsRepository): Response
    {
        return $this->render('management/user/culture.html.twig', [
            'culture' => $culture,
            'interventions' => $interventionsRepository
        ]);
    }
}

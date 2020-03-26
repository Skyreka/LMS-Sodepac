<?php

namespace App\Controller;

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

class ViewUserController extends AbstractController
{
    /**
     * @Route("view/user/{id}", name="view.user.index")
     * @param Users $customer
     * @param StocksRepository $sr
     * @param IlotsRepository $ir
     * @param IrrigationRepository $irrigationRepo
     * @param AnalyseRepository $analyseRepo
     * @return Response
     */
    public function index(Users $customer, StocksRepository $sr, IlotsRepository $ir, IrrigationRepository $irrigationRepo, AnalyseRepository $analyseRepo): Response
    {
        $exploitationOfCustomer = $customer->getExploitation();
        $usedProducts = $sr->findByExploitation( $exploitationOfCustomer, true );
        $ilots = $ir->findBy( ['exploitation' => $exploitationOfCustomer], null, '7' );
        $irrigations = $irrigationRepo->findByExploitation( $exploitationOfCustomer, 7 );
        $analyses = $analyseRepo->findByExploitation( $exploitationOfCustomer, 7 );
        return $this->render('view/user/index.html.twig', [
            'customer' => $customer,
            'usedProducts' => $usedProducts,
            'ilots' => $ilots,
            'irrigations' => $irrigations,
            'analyses' => $analyses
        ]);
    }

    /**
     * @Route("view/user/ilot/{id}", name="view.user.ilot")
     * @param Ilots $ilot
     * @param CulturesRepository $cr
     * @return Response
     */
    public function showIlots(Ilots $ilot, CulturesRepository $cr): Response
    {
        $customer = $ilot->getExploitation()->getUsers();
        $cultures = $cr->findBy( ['ilot' => $ilot] );

        return $this->render('view/user/ilot.html.twig', [
            'customer' => $customer,
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("view/user/culture/{id}", name="view.user.culture")
     * @param Cultures $culture
     * @param InterventionsRepository $interventionsRepository
     * @return Response
     */
    public function showCulture(Cultures $culture, InterventionsRepository $interventionsRepository): Response
    {
        return $this->render('view/user/culture.html.twig', [
            'culture' => $culture,
            'interventions' => $interventionsRepository
        ]);
    }
}
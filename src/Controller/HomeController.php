<?php
namespace App\Controller;

use App\Repository\IlotsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    /**
     * @Route("/", name="home")
     * @param IlotsRepository $ir
     * @param UsersRepository $ur
     * @return Response
     */
    public function index(IlotsRepository $ir, UsersRepository $ur): Response {
        $ilots = $ir->findIlotsFromUser( $this->getUser()->getExploitation() );
        $bsvs = $this->getUser()->getBsvs();
        return $this->render('pages/home.html.twig', [
            'bsvs' => $bsvs,
            'ilots' => $ilots
        ]);
    }
}
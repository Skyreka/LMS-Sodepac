<?php
namespace App\Controller;

use App\Repository\IlotsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    /**
     * @Route("/home", name="home")
     * @param IlotsRepository $ir
     * @param UsersRepository $ur
     * @return Response
     */
    public function home(IlotsRepository $ir, UsersRepository $ur): Response {
        $ilots = $ir->findIlotsFromUser( $this->getUser()->getExploitation() );
        $bsvs = $this->getUser()->getBsvs();
        $panoramas = $this->getUser()->getPanoramas();
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
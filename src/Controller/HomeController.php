<?php
namespace App\Controller;

use App\Repository\IlotsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    /**
     * @Route("/", name="home")
     * @param IlotsRepository $ilotsRepository
     * @return Response
     */
    public function index(IlotsRepository $ilotsRepository): Response {
        $ilots = $ilotsRepository->findAll();
        return $this->render('pages/home.html.twig', [
            'ilots' => $ilots
        ]);
    }
}
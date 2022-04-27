<?php

namespace App\Http\Controller;

use App\Domain\Bsv\Repository\BsvUsersRepository;
use App\Domain\Panorama\Repository\PanoramaSendRepository;
use App\Domain\Recommendation\Repository\RecommendationsRepository;
use App\Domain\Ticket\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(
        BsvUsersRepository $bur,
        PanoramaSendRepository $pur,
        TicketsRepository $tr,
        RecommendationsRepository $rr
    ): Response
    {
        $flashs          = $bur->findAllByCustomer($this->getUser(), 3);
        $panoramas       = $pur->findAllByCustomer($this->getUser(), 3);
        $tickets         = $tr->findAllByUser($this->getUser(), 3);
        
        //-- Clear listCulture
        $this->container->get('session')->remove('listCulture');
        
        return $this->render('pages/home.html.twig', [
            'flashs' => $flashs,
            'panoramas' => $panoramas,
            'tickets' => $tickets
        ]);
    }
    
    /**
     * @Route("/cgv", name="cgv_index")
     */
    public function cgv(): RedirectResponse
    {
        return $this->redirect('help/cgv/cgv.pdf');
    }
}

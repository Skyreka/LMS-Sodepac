<?php

namespace App\Controller;

use App\Repository\PanoramasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TechnicianPanoramasController extends AbstractController
{
    /**
     * @var PanoramasRepository
     */
    private $panoramasRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PanoramasRepository $panoramasRepository, EntityManagerInterface $em)
    {
        $this->panoramasRepository = $panoramasRepository;
        $this->em = $em;
    }

    /**
     * @Route("technician/panoramas", name="technician.panoramas.index")
     */
    public function index(): Response
    {
        $panoramas = $this->panoramasRepository->findAllPanoramasOfTechnician( $this->getUser()->getId() );
        return $this->render('technician/panoramas/index.html.twig', [
            'panoramas' => $panoramas
        ]);
    }


}
<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Repository\CulturesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @var CulturesRepository
     */
    private $repository;

    public function __construct(CulturesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response {
        /* INSERT QUERY
        $cultures = new Cultures();
        $cultures->setName('Salut');
        $em = $this->getDoctrine()->getManager();
        $em->persist($cultures);
        $em->flush();
        */
        $culture = $this->repository->find(1);
        dump($culture);
        return $this->render('pages/home.html.twig');
    }
}
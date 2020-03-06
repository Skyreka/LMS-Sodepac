<?php

namespace App\Controller;

use App\Repository\BsvRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBsvController extends AbstractController
{
    /**
     * @var BsvRepository
     */
    private $repositoryBsv;

    public function __construct(BsvRepository $repository)
    {
        $this->repositoryBsv = $repository;
    }

    /**
     * @Route("/admin/bsv", name="admin.bsv.index")
     * @return Response
     */
    public function index(): Response
    {
        $bsv = $this->repositoryBsv->findAllNotSent();
        dump($bsv);
        return $this->render('admin/bsv/index.html.twig', [
            'bsv' => $bsv
        ]);
    }
}

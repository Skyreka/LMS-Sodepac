<?php

namespace App\Controller\SuperAdmin;

use App\Repository\RecommendationProductsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class SuperAdminController
 * @package App\Controller
 * @Route("/super-admin")
 * @IsGranted("ROLE_SUPERADMIN")
 */
class SuperAdminController extends AbstractController {


    /**
     * @Route("/", name="superadmin_index", methods={"GET"})
     * @param UsersRepository $ur
     * @return Response
     */
    public function index( UsersRepository $ur )
    {
        $customers = $ur->countAllByRole('ROLE_USER');

        $inactiv = $ur->countAllByPack('DISABLE');
        $inactivPercent = 100 * $inactiv / $customers;

        $full = $ur->countAllByPack('PACK_FULL');
        $fullPercent = 100 * $full / $customers;

        $light = $ur->countAllByPack('PACK_LIGHT');
        $lightPercent = 100 * $light / $customers;

        $demo = $ur->countAllByPack('PACK_DEMO');
        $demoPercent = 100 * $demo / $customers;

        return $this->render('super_admin/index.html.twig', [
            'inactivCount' => $inactiv,
            'inactivPercent' => $inactivPercent,
            'fullCount' => $full,
            'fullPercent' => $fullPercent,
            'lightCount' => $light,
            'lightPercent' => $lightPercent,
            'demoCount' => $demo,
            'demoPercent' => $demoPercent
        ]);
    }
}

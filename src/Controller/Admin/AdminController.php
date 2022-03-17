<?php
namespace App\Controller\Admin;

use App\Repository\RecommendationProductsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("", name="admin_index", methods={"GET"})
     * @param UsersRepository $ur
     * @param RecommendationProductsRepository $rpr
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index( UsersRepository $ur, RecommendationProductsRepository $rpr)
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

        return $this->render('admin/index.html.twig', [
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

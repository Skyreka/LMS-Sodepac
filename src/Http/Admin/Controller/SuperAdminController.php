<?php

namespace App\Http\Admin\Controller;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Intervention\Repository\InterventionsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SuperAdminController
 * @package App\Controller
 * @Route("/super-admin", name="superadmin_")
 * @IsGranted("ROLE_SUPERADMIN")
 */
class SuperAdminController extends AbstractController
{
    
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UsersRepository $ur, InterventionsRepository $ir)
    {
        $customers = $ur->countAllByRole('ROLE_USER');
        
        $inactiv        = $ur->countAllByPack('DISABLE');
        $inactivPercent = 100 * $inactiv / $customers;
        
        $full        = $ur->countAllByPack('PACK_FULL');
        $fullPercent = 100 * $full / $customers;
        
        $light        = $ur->countAllByPack('PACK_LIGHT');
        $lightPercent = 100 * $light / $customers;
        
        $demo        = $ur->countAllByPack('PACK_DEMO');
        $demoPercent = 100 * $demo / $customers;
        
        return $this->render('super_admin/index.html.twig', [
            'inactivCount' => $inactiv,
            'inactivPercent' => $inactivPercent,
            'fullCount' => $full,
            'fullPercent' => $fullPercent,
            'lightCount' => $light,
            'lightPercent' => $lightPercent,
            'demoCount' => $demo,
            'demoPercent' => $demoPercent,
            'interventions' => $ir->findBy([], ['intervention_at' => 'DESC'], '200')
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseContractController
 * @package App\Controller
 */
class PurchaseContractController extends AbstractController
{
    /**
     * @return Response
     * @Route("/management/purchase-contract/", name="management_purchase_contract_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render( 'management/purchase-contract/index.html.twig' );
    }

    /**
     * @return Response
     * @Route("/management/purchase-contract/new", name="purchase_contract_new", methods={"GET", "POST"})
     */
    public function new(): Response
    {
        return $this->render( 'management/purchase-contract/new.html.twig' );
    }
}

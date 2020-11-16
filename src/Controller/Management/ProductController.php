<?php
namespace App\Controller\Management;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller\Management
 * @Route("/management/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="management_products")
     * @param ProductsRepository $pd
     * @return Response
     */
    public function admin( ProductsRepository $pd): Response
    {
        $products = $pd->findAll();

        return $this->render('management/products/index.html.twig', [
            'products' => $products
        ]);
    }
}

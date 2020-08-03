<?php
namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/products", name="admin.products")
     * @param ProductsRepository $pd
     * @return Response
     */
    public function admin( ProductsRepository $pd): Response
    {
        $products = $pd->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/technician/products", name="technician.products")
     * @param ProductsRepository $pd
     * @return Response
     */
    public function technician( ProductsRepository $pd): Response
    {
        $products = $pd->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }
}
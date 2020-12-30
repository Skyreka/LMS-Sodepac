<?php
namespace App\Controller\Management;

use App\Repository\ProductsRepository;
use DataTables\DataTablesInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function index( Request $request ): Response
    {
        $order = null;
        if ( $request->query->get('order') ) {
            $order = $request->query->get('order');
        }

        return $this->render('management/products/index.html.twig', ['order' => $order]);
    }

    /**
     * @Route("/data", name="management_products_data", methods={"GET"})
     * @param Request $request
     * @param DataTablesInterface $datatables
     * @return JsonResponse
     */
    public function data(Request $request, DataTablesInterface $datatables): JsonResponse
    {
        try {
            $results = $datatables->handle($request, 'products');

            return $this->json($results);
        }
        catch (HttpException $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        }
    }
}

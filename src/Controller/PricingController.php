<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Class SalesController
 * @package App\Controller
 * @Route("/pricing")
 */
class PricingController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SalesController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * @param ProductsRepository $pr
     * @return Response
     * @Route("/", name="pricing_index", methods={"GET", "POST"})
     */
    public function index( ProductsRepository $pr ): Response
    {
        return $this->render('pricing/index.html.twig', [
            'products' => $pr->findAll()
        ]);
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("/product/edit", name="pricing_product_edit")
     * @param Request $request
     * @param ProductsRepository $pr
     * @return JsonResponse
     */
    public function editProduct(Request $request, ProductsRepository $pr ): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $product = $pr->find($request->get('id'));

            if ($request->get('price')) {
                $product->setPrice( (float) $request->get('price'));
            }

            $this->em->flush();

            return new JsonResponse(["type" => 'success'], 200);
        }
        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error',
            404
        ]);
    }
}

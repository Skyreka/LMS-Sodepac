<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use App\Repository\ProductsRepository;
use DataTables\DataTablesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use TreeHouse\Slugifier\Slugifier;

/**
 * Class SalesController
 * @package App\Controller
 * @Route("pricing")
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
     * @return Response
     * @Route("", name="pricing_index", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        return $this->render('pricing/index.html.twig');
    }

    /**
     * @Route("products/data", name="pricing_products_data", methods={"GET"})
     * @param Request $request
     * @param DataTablesInterface $datatables
     * @return JsonResponse
     */
    public function data(Request $request, DataTablesInterface $datatables): JsonResponse
    {
        try {
            $results = $datatables->handle($request, 'pricing');

            return $this->json($results);
        }
        catch (HttpException $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("product/new", name="pricing_product_new", methods={"GET", "POST"})
     */
    public function new( Request $request ): Response
    {
        $product = new Products();
        $slugify = new Slugifier();
        $form = $this->createForm( ProductType::class, $product );
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug( $slugify->slugify( $form->get('name')->getViewData() ) );
            $this->em->persist( $product );
            $this->em->flush();
            return $this->redirectToRoute('pricing_index', ['filterBy' => 2]);
        }

        return $this->render('pricing/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Products $product
     * @param Request $request
     * @return Response
     * @Route("product/edit/{id}", name="pricing_product_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit( Products $product, Request $request ): Response
    {
        if ( $product->getIdLex() != NULL ) {
            $form = $this->createForm( ProductType::class, $product, [ 'edit_name' => false ] );
        } else {
            $form = $this->createForm( ProductType::class, $product, [ 'edit_name' => true ] );
        }
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('pricing_index');
        }

        return $this->render('pricing/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("product/update/{id}", name="pricing_product_update", methods={"UPDATE"}, requirements={"id":"\d+"})
     * @param Products $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProduct(Products $product, Request $request ): RedirectResponse
    {
        if ( $product->getIsActive() ) {
            $product->setIsActive( 0 );
        } else {
            $product->setIsActive( 1 );
        }
        $this->em->flush();
        return $this->redirectToRoute('pricing_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use App\Repository\ProductsRepository;
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
     * @param Request $request
     * @return Response
     * @Route("/", name="pricing_index", methods={"GET", "POST"})
     */
    public function index( ProductsRepository $pr, Request $request ): Response
    {
        $filterBy = $request->get('filterBy');

        if ( $filterBy ) {
            $products = $pr->findBy( ['category' => $filterBy ], ['name' => 'ASC'] );
        } else {
            $products = $pr->findAll();
        }

        return $this->render('pricing/index.html.twig', [
            'products' => $products,
            'filterBy' => $filterBy
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

            if ($request->get('name')) {
                $product->setName(  $request->get('name'));
            }

            if ($request->get('price')) {
                $product->setPrice( (float) $request->get('price'));
            }

            if ($request->get('rpd')) {
                $product->setRpd( (float) $request->get('rpd'));
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

    /**
     * @param Request $request
     * @return Response
     * @Route("/product/new", name="pricing_product_new", methods={"GET", "POST"})
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
     * @Route("product/update/{id}", name="pricing_product_update", methods={"UPDATE"}, requirements={"id":"\d+"})
     * @param Products $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProduct(Products $product, Request $request ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('update_product' . $product->getId(), $request->get('_token'))) {
            if ( $request->get('action') === "0" ) {
                $product->setIsActive( 1 );
            } elseif ( $request->get('action') === "1" ) {
                $product->setIsActive( 0 );
            }
            $this->em->flush();
        }
        return $this->redirectToRoute('pricing_index');
    }
}

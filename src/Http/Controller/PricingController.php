<?php

namespace App\Http\Controller;

use App\Controller\HttpException;
use App\Domain\Product\Entity\Products;
use App\Domain\Product\Form\ProductType;
use Cocur\Slugify\Slugify;
use DataTables\DataTablesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TreeHouse\Slugifier\Slugifier;

/**
 * Class SalesController
 * @package App\Controller
 * @Route("/pricing", name="pricing_")
 */
class PricingController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @return Response
     * @Route("/", name="index", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        return $this->render('pricing/index.html.twig');
    }
    
    /**
     * @Route("/products/data", name="products_data", methods={"GET"})
     */
    public function data(Request $request, DataTablesInterface $datatables): JsonResponse
    {
        try {
            $results = $datatables->handle($request, 'pricing');
            
            return $this->json($results);
        } catch(HttpException $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        }
    }
    
    /**
     * @Route("/product/new", name="product_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Products();
        $slugify = new Slugify();
        $form    = $this->createForm(ProductType::class, $product, ['edit_name' => true]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugify->slugify($form->get('name')->getViewData()));
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('pricing_index', ['filterBy' => 2]);
        }
        
        return $this->render('pricing/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/product/edit/{id}", name="product_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Products $product, Request $request): Response
    {
        if(NULL != $product->getIdLex() ) {
            $form = $this->createForm(ProductType::class, $product, ['edit_name' => false]);
        } else {
            $form = $this->createForm(ProductType::class, $product, ['edit_name' => true]);
        }
    
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('pricing_index');
        }
        
        return $this->render('pricing/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/product/update/{id}", name="product_update", methods={"UPDATE"}, requirements={"id":"\d+"})
     */
    public function updateProduct(Products $product, Request $request): RedirectResponse
    {
        if($product->getIsActive()) {
            $product->setIsActive(0);
        } else {
            $product->setIsActive(1);
        }
        $this->em->flush();
        return $this->redirectToRoute('pricing_index');
    }
}

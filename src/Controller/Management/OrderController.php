<?php
namespace App\Controller\Management;

use App\Entity\Orders;
use App\Entity\OrdersProduct;
use App\Entity\RecommendationProducts;
use App\Entity\Recommendations;
use App\Repository\OrdersProductRepository;
use App\Repository\CulturesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CardController
 * @package App\Controller\Management
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/show/{id_number}", name="order_show", methods={"GET", "POST"})
     * @param Orders $orders
     * @param OrdersProductRepository $cpr
     * @return Response
     */
    public function show(Orders $orders, OrdersProductRepository $cpr): Response
    {

        $products = $cpr->findBy( ['orders' => $orders] );

        return $this->render('management/order/show.html.twig', [
            'order' => $orders,
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/add/{product}/{recommendation}", name="order_product_add", methods={"ADDTOCARD"}, requirements={"product":"\d+", "recommendation":"\d+"})
     * @param RecommendationProducts $product
     * @param Recommendations $recommendation
     * @param OrdersProductRepository $opr
     * @return RedirectResponse
     */
    public function addProduct( RecommendationProducts $product, Recommendations $recommendation, OrdersProductRepository $opr): RedirectResponse
    {
        // Check if order already exist on this session
        if ($this->container->get('session')->get('currentOrder') == NULL) {
            $order = new Orders();
            $order->setStatus( 0 );
            $order->setIdNumber( strtoupper(uniqid( 'C' )) );
            $order->setCreator( $this->getUser() );
            $order->setCustomer( $product->getRecommendation()->getExploitation()->getUsers());
            $this->em->persist( $order );
            $this->em->flush();

            // Add id to session
            $this->container->get('session')->set('currentOrder', $order);

            // Add first product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $order );
            $orderProduct->setProduct( $product->getProduct() );
            $orderProduct->setTotalQuantity( $product->getQuantity() );
            $this->em->persist( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('success', 'Nouveau panier temporaire crée avec succès');
            $this->addFlash('info', 'Le produit '. $product->getProduct()->getName() .' a été ajouté au panier.');
        } else {
            //Clear Entity Manger
            $this->em->clear();

            // Get current Order
            $currentOrder = $this->container->get('session')->get('currentOrder');
            if ($opr->findBy(['orders' => $currentOrder, 'product' => $product->getProduct()])) {
                $this->addFlash('danger', 'Le produit '. $product->getProduct()->getName() .' est déjà présent dans le panier. ('. $currentOrder->getIdNumber() .')');
                return $this->redirectToRoute('recommendation_summary' , ['id' => $recommendation->getId()]);
            }
            // Check duplicate


            // Add new product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $currentOrder );
            $orderProduct->setProduct( $product->getProduct() );
            $orderProduct->setTotalQuantity( $product->getQuantity() );
            $this->em->merge( $orderProduct );
            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $product->getProduct()->getName() .' a été ajouté au panier. ('. $currentOrder->getIdNumber() .')');
        }

        //Return to summary
        return $this->redirectToRoute('recommendation_summary' , ['id' => $recommendation->getId()]);
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("/product/edit", name="order_product_edit")
     * @param Request $request
     * @param CulturesRepository $cr
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editDose(Request $request, OrdersProductRepository $cr )
    {
        if ($request->isXmlHttpRequest()) {
            $orderProduct = $cr->find($request->get('id'));

            if ($request->get('unitPrice')) {
                $orderProduct->setUnitPrice( (float) $request->get('unitPrice'));
            }

            if ($request->get('totalQuantity')) {
                $orderProduct->setTotalQuantity( (float) $request->get('totalQuantity'));
            }

            if ($request->get('conditioning')) {
                $orderProduct->setConditioning( $request->get('conditioning'));
            } elseif ($request->get('conditioning') === '') {
                $orderProduct->setConditioning( NULL );
            }

            if ($request->get('discount')) {
                $orderProduct->setDiscount( (float) $request->get('discount'));
            } elseif ($request->get('discount') === '') {
                $orderProduct->setDiscount( NULL );
            }

            if ($request->get('taxe')) {
                $orderProduct->setTaxe( (float) $request->get('taxe'));
            } elseif ($request->get('taxe') === '') {
                $orderProduct->setTaxe( NULL );
            }

            $this->em->flush();

            $total = $orderProduct->getUnitPrice() * $orderProduct->getTotalQuantity();
            return new JsonResponse(["type" => 'success', "total" => $total], 200);
        }
        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error',
            404
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="order_product_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     * @param OrdersProduct $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(OrdersProduct $product, Request $request )
    {
        if ($this->isCsrfTokenValid('deleteOrderArticle' . $product->getId(), $request->get('_token'))) {
            $this->em->remove( $product );
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('order_show', ['id_number' => $product->getOrder()->getIdNumber()]);
    }
}

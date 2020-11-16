<?php
namespace App\Controller\Management;

use App\Entity\Orders;
use App\Entity\OrdersProduct;
use App\Entity\Products;
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
     * @Route("/pdf/{id_number}/{print}", name="order_pdf_view", methods={"GET", "POST"}, defaults={"print"=false}, requirements={"print":"true|false"})
     * @param Orders $order
     * @param OrdersProductRepository $opr
     * @param Request $request
     * @return Response
     */
    public function pdfView( Orders $order, OrdersProductRepository $opr, Request $request): Response
    {
        // Security
        if( $order->getCustomer() != $this->getUser() AND $order->getCreator() != $this->getUser() AND $request->get('print') == false) {
            throw $this->createNotFoundException('Vous n\'avez pas la permission de voir ce document.');
        }
        if( $order->getStatus() < 2) {
            throw $this->createNotFoundException('Cette commande n\'est pas encore validée.');
        }

        // List of product
        $products = $opr->findBy( ['orders' => $order] );

        return $this->render('management/order/pdf.html.twig', [
            'order' =>$order,
            'products' =>  $products,
            'print' => $request->get('print')
        ]);
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
     * @Route("/product/add/{product}/{recommendation}", name="order_product_add", methods={"ADDTOORDER"}, requirements={"product":"\d+", "recommendation":"\d+"})
     * @Route("/product/add/{id}", name="order_product_other_add", methods={"ADDTOORDER"}, requirements={"product":"\d+", "recommendation":"\d+"})
     * @param RecommendationProducts $product
     * @param Recommendations $recommendation
     * @param OrdersProductRepository $opr
     * @param Products $id
     * @return RedirectResponse
     */
    public function addProduct( ?RecommendationProducts $product, ?Recommendations $recommendation, OrdersProductRepository $opr, ?Products $id): RedirectResponse
    {
        $isOther = 0;
        // Get Action
        if ($recommendation == NULL) {
            $isOther = 1;
            $product = $id;
        }

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
            if ($isOther) {
                $p = $product;
            } else {
                $p = $product->getProduct();
            }
            if ($opr->findBy(['orders' => $currentOrder, 'product' => $p])) {
                $this->addFlash('danger', 'Le produit '. $p->getName() .' est déjà présent dans le panier. ('. $currentOrder->getIdNumber() .')');
                return $this->redirectToRoute('recommendation_summary' , ['id' => $recommendation->getId()]);
            }
            // Check duplicate


            // Add new product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $currentOrder );
            $orderProduct->setProduct( $p );
            if (!$isOther) {
                $orderProduct->setTotalQuantity( $product->getQuantity() );
            }
            $this->em->merge( $orderProduct );
            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $p->getName() .' a été ajouté au panier. ('. $currentOrder->getIdNumber() .')');
        }

        // Return to product select
        if ($isOther) {
            return $this->redirectToRoute('management_products');
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
    public function editProduct(Request $request, OrdersProductRepository $cr ): JsonResponse
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
    public function delete(OrdersProduct $product, Request $request ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('deleteOrderArticle' . $product->getId(), $request->get('_token'))) {
            $this->em->remove( $product );
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('order_show', ['id_number' => $product->getOrder()->getIdNumber()]);
    }

    /**
     * @Route("/save/{id}", name="order_save", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @return Response
     */
    public function save( Orders $order ): Response
    {
        //Update status
        if ( $order->getStatus() == 0 ) {
            $order->setStatus( 1 );
            $this->em->flush();
        }

        return $this->redirectToRoute('order_show', ['id_number' => $order->getIdNumber()]);
    }

    /**
     * @Route("/valid/{id}", name="order_valid", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @param \Swift_Mailer $mailer
     * @param OrdersProductRepository $opr
     * @return Response
     */
    public function valid( Orders $order, \Swift_Mailer $mailer, OrdersProductRepository $opr): Response
    {
        //Security
        if ( $order->getStatus() == 1 ) {
            // Update status
            $order->setStatus( 2 );
            $order->setCreateDate( new \DateTime() );

            // Send to depot
            $message = (new \Swift_Message('#'. $order->getIdNumber() . ' Nouvelle commande de ' . $order->getCreator()->getIdentity()))
                ->setFrom('send@lms-sodepac.fr')
                ->setTo( $order->getCustomer()->getWarehouse()->getEmail() )
                ->setBody(
                    $this->renderView(
                        'management/order/email/warehouse.html.twig', [
                            'order' => $order
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            $this->em->flush();

            // Msg
            $this->addFlash('success', 'Commande validé avec succès, envoie au dépot en cours...');
        }

        return $this->redirectToRoute('order_show', ['id_number' => $order->getIdNumber()]);
    }
}

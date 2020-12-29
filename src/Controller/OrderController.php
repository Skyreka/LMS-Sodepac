<?php
namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersProduct;
use App\Entity\Products;
use App\Entity\RecommendationProducts;
use App\Entity\Recommendations;
use App\Form\OrderAdditionalType;
use App\Form\OrdersType;
use App\Repository\OrdersProductRepository;
use App\Repository\CulturesRepository;
use App\Repository\OrdersRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * Class CardController
 * @package App\Controller\Management
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
     * @Route("/management/order", name="order_index", methods={"GET"})
     * @param OrdersRepository $op
     * @return Response
     */
    public function index(OrdersRepository $op): Response
    {
        if ($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $orders = $op->findByTechnician( $this->getUser(), 10 );
        } else {
            $orders = $op->findByAdmin();
        }

        return $this->render('management/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/pdf/{id_number}/{print}", name="order_pdf_view", methods={"GET", "POST"}, defaults={"print"=false}, requirements={"print":"true|false"})
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
     * @Route("management/order/show/{id_number}", name="order_show", methods={"GET", "POST"})
     * @param Orders $orders
     * @param OrdersProductRepository $cpr
     * @param Request $request
     * @return Response
     */
    public function show(Orders $orders, OrdersProductRepository $cpr, Request $request): Response
    {
        $products = $cpr->findBy( ['orders' => $orders] );
        $form = $this->createForm( OrderAdditionalType::class, $orders);
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->em->flush();
            $this->addFlash('success', 'Information sauvegardé avec succès');
            return $this->redirectToRoute('order_show', ['id_number' => $orders->getIdNumber()]);
        }

        return $this->render('management/order/show.html.twig', [
            'order' => $orders,
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("management/order/new", name="order_new", methods={"GET", "POST"})
     * @return Response
     */
    public function new( Request $request ): Response
    {
        $order = new Orders;
        $form = $this->createForm( OrdersType::class, $order);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setStatus( 0 );
            $order->setIdNumber( strtoupper(uniqid( 'C' )) );
            $order->setCreator( $this->getUser() );

            $this->em->persist( $order );
            $this->em->flush();

            // Add id to session and reset if exist
            $this->container->get('session')->remove('currentOrder');
            $this->container->get('session')->set('currentOrder', $order);

            $this->addFlash('success', 'Nouveau panier temporaire crée avec succès');
            return $this->redirectToRoute('order_show', ['id_number' => $order->getIdNumber()]);
        }

        return $this->render('management/order/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("management/order/new/order_select_data", name="order_select_data")
     * @param Request $request
     * @param UsersRepository $ur
     * @return JsonResponse
     */
    public function newSelectData( Request $request, UsersRepository $ur): JsonResponse
    {
        //Get information from ajax call
        $term = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        //Query of like call
        if ($this->getUser()->getStatus() == 'ROLE_ADMIN') {
            $users = $ur->createQueryBuilder('u')
                ->where('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->setMaxResults( $limit )
                ->getQuery()
                ->getResult()
            ;
        } else {
            // Technician view only them users
            $users = $ur->createQueryBuilder('u')
                ->where('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->andWhere('u.technician = :tech')
                ->setParameter(':tech', $this->getUser())
                ->setMaxResults( $limit )
                ->getQuery()
                ->getResult()
            ;
        }

        // Return Array of key = id && text = value
        $array = [];
        foreach ($users as $user) {
            $array[] = array(
                'id' => $user->getId(),
                'text' => $user->getIdentity()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }

    /**
     * @Route("management/order/product/add/{product}/{recommendation}", name="order_product_add", methods={"ADDTOORDER"}, requirements={"product":"\d+", "recommendation":"\d+"})
     * @Route("management/order/product/add/{id}", name="order_product_other_add", methods={"ADDTOORDER"}, requirements={"id":"\d+"})
     * @param RecommendationProducts|null $product
     * @param Recommendations|null $recommendation
     * @param OrdersRepository $or
     * @param OrdersProductRepository $opr
     * @param Request $request
     * @param Products|null $id
     * @return RedirectResponse
     */
    public function addProduct(
        ?RecommendationProducts $product,
        ?Recommendations $recommendation,
        OrdersRepository $or,
        OrdersProductRepository $opr,
        Request $request,
        ?Products $id): RedirectResponse
    {
        $isOther = 0;
        // Get Action
        if (!isset($recommendation)) {
            $isOther = 1;
            $product = $id;
            $orderFromOther = $or->findOneBy( ['id_number' => $request->query->get('orderNumber')] );
        }

        // Check if order already exist on this session
        if ($this->container->get('session')->get('currentOrder') == NULL && !isset($orderFromOther)) {
            // Redirect to new cart if user want to create from products
            if ( $isOther == 1) {
                return $this->redirectToRoute('order_new');
            }

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
            $orderProduct->setQuantity( $product->getQuantity() );
            $orderProduct->setUnitPrice( 0 );
            $this->em->persist( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('success', 'Nouveau panier temporaire crée avec succès');
            $this->addFlash('info', 'Le produit '. $product->getProduct()->getName() .' a été ajouté au panier.');
        } elseif ($this->container->get('session')->get('currentOrder') == NULL && isset($orderFromOther) && $isOther = 1) {
            // Function if user want to add produit to saved order

            // Add first product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $orderFromOther );
            $orderProduct->setProduct( $product );
            $this->em->persist( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $product->getName() .' a été ajouté a la commande.');
            return $this->redirectToRoute('order_show' , ['id_number' => $request->query->get('orderNumber')]);
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
            // product already on this order and same
            $productOnDb = $opr->findOneBy(['orders' => $currentOrder, 'product' => $p]);
            if ( $productOnDb ) {
                if ( $isOther) {
                    $this->addFlash('danger', 'Ce produit est déjà dans le panier en cours.');
                    return $this->redirectToRoute('management_products');
                }
                $productOnDb->setQuantity( $productOnDb->getQuantity() + $product->getQuantity() );
                $this->em->flush();
                return $this->redirectToRoute('recommendation_summary' , ['id' => $recommendation->getId()]);
            }
            // Check duplicate


            // Add new product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $currentOrder );
            $orderProduct->setProduct( $p );
            $orderProduct->setUnitPrice( 0 );
            if (!$isOther) {
                $orderProduct->setQuantity( $product->getQuantity() );
                $orderProduct->setTotalQuantity( 0 );
            } else {
                $orderProduct->setTotalQuantity( 0 );
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
     * @Route("management/order/product/edit", name="order_product_edit")
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
     * @Route("management/order/product/delete/{id}", name="order_product_delete", methods={"DELETE"}, requirements={"id":"\d+"})
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
     * @Route("management/order/save/{id}", name="order_save", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @return Response
     */
    public function save( Orders $order ): Response
    {
        //Update status
        if ( $order->getStatus() == 0 ) {
            $order->setStatus( 1 );
            $this->em->flush();

            //Remove Cart
            $this->container->get('session')->remove('currentOrder');
        }

        return $this->redirectToRoute('order_show', ['id_number' => $order->getIdNumber()]);
    }

    /**
     * @Route("management/order/valid/{id}", name="order_valid", methods={"GET", "POST"}, requirements={"id":"\d+"})
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
                        'management/order/email/send.html.twig', [
                            'order' => $order
                        ]
                    ),
                    'text/html'
                )
            ;
            $messageCustomer = (new \Swift_Message('#'. $order->getIdNumber() . ' Nouvelle commande de ' . $order->getCreator()->getIdentity()))
                ->setFrom('send@lms-sodepac.fr')
                ->setTo( $order->getCustomer()->getEmail() )
                ->setBody(
                    $this->renderView(
                        'management/order/email/send.html.twig', [
                            'order' => $order
                        ]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $mailer->send($messageCustomer);

            $this->em->flush();

            // Msg
            $this->addFlash('success', 'Commande validé avec succès, envoie au dépot en cours...');
        }

        return $this->redirectToRoute('order_show', ['id_number' => $order->getIdNumber()]);
    }

    /**
     * @Route("/orders", name="user_order_index", methods={"GET", "POST"})
     * @param OrdersRepository $op
     * @return Response
     */
    public function userIndex( OrdersRepository $op ): Response
    {
        return $this->render('exploitation/orders/index.html.twig', [
            'orders' => $op->findByUser( $this->getUser() )
        ]);
    }


    /**
     * @Route("/management/order/synthesis", name="order_synthesis", methods={"GET", "POST"})
     * @param OrdersRepository $op
     * @return Response
     */
    public function synthesis( OrdersRepository $op ): Response
    {
        if ($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $orders = $op->findByTechnician( $this->getUser() );
        } else {
            $orders = $op->findAll();
        }

        return $this->render('management/order/synthesis/index.html.twig', [
            'orders' => $orders
        ]);
    }
}

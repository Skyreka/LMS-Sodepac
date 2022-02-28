<?php

namespace App\Controller\Management;

use App\AsyncMethodService;
use App\Entity\Orders;
use App\Entity\OrdersProduct;
use App\Entity\Products;
use App\Entity\Recommendations;
use App\Entity\Signature;
use App\Entity\SignatureOtp;
use App\Form\OrderAdditionalType;
use App\Form\OrdersAddProductFieldType;
use App\Form\OrdersAddProductType;
use App\Form\OrdersType;
use App\Repository\OrdersProductRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use App\Service\EmailNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class OrderController
 * @package App\Controller\Management
 */
class UserController extends AbstractController
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
     * @Route("/management/order", name="management_order_index", methods={"GET"})
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
        if ( $this->getUser() ) {
            if( $order->getCustomer() != $this->getUser() AND $order->getCreator() != $this->getUser() AND $this->getUser()->getStatus() != 'ROLE_ADMIN') {
                throw $this->createNotFoundException('Vous n\'avez pas la permission de voir ce document.');
            }
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
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
     * @Route("/management/order/show/{id_number}", name="management_order_show", methods={"GET", "POST"})
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
            $this->addFlash('success', 'Information sauvegardée avec succès');
            return $this->redirectToRoute('management_order_show', ['id_number' => $orders->getIdNumber()]);
        }

        return $this->render('management/order/show.html.twig', [
            'order' => $orders,
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("management/order/delete/{id}", name="management_order_delete_recorded", methods="DELETE", requirements={"id":"\d+"})
     * @param Orders $orders
     * @param Request $request
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function deleteRecorded(Orders $orders, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $orders->getId(), $request->get('_token' ))) {
            $this->em->remove($orders);
            $this->em->flush();

            $this->container->get('session')->remove('currentOrder');

            $this->addFlash('success', 'Commande supprimée avec succès');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
        }
        return $this->redirectToRoute('order_index');
    }

    /**
     * @Route("management/order/new", name="management_order_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function new( Request $request ): Response
    {
        $order = new Orders();
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

            $this->addFlash('success', 'Nouveau panier temporaire créé avec succès');
            return $this->redirectToRoute('management_order_show', ['id_number' => $order->getIdNumber()]);
        }

        return $this->render('management/order/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/management/select_users", name="_management_select_users")
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
                ->orWhere('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->orWhere('u.company LIKE :company')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->setParameter('company', '%' . $term . '%')
                ->setMaxResults( $limit )
                ->getQuery()
                ->getResult()
            ;
        } else {
            // Technician view only them users
            $users = $ur->createQueryBuilder('u')
                ->orWhere('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->orWhere('u.company LIKE :company')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->setParameter('company', '%' . $term . '%')
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
                'text' => $user->getIdentity() . '(' . $user->getCompany() . ')'
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }

    /**
     * @Route("/management/order/product/add/{recommendation}", name="management_order_product_add", methods={"ADDTOORDER"}, requirements={"recommendation":"\d+"})
     * @param Recommendations $recommendation
     * @param OrdersProductRepository $opr
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addProduct(
        Recommendations $recommendation,
        OrdersProductRepository $opr
    ): RedirectResponse
    {
        // Check if order already exist on this session
        if ($this->container->get('session')->get('currentOrder') == NULL) {
            $order = new Orders();
            $order->setStatus( 0 );
            $order->setIdNumber( strtoupper(uniqid( 'C' )) );
            $order->setCreator( $this->getUser() );
            $order->setCustomer( $recommendation->getExploitation()->getUsers());
            $this->em->persist( $order );
            $this->em->flush();

            // Add id to session
            $this->container->get('session')->set('currentOrder', $order);

            $products = [];
            foreach( $recommendation->getRecommendationProducts() as $recommendationProducts ) {

                if ( !in_array($recommendationProducts->getProduct(), $products) ) {
                    // Add  product
                    $orderProduct = new OrdersProduct();
                    $orderProduct->setOrder($order);
                    $orderProduct->setProduct( $recommendationProducts->getProduct() );
                    $orderProduct->setQuantity( $recommendationProducts->getQuantity() );
                    $orderProduct->setTotalQuantity(0);
                    $orderProduct->setUnitPrice($recommendationProducts->getProduct()->getPrice() ? $recommendationProducts->getProduct()->getPrice() : 0 );

                    $this->em->persist($orderProduct);
                    $this->em->flush();
                } else {
                    // Update quantity
                    $orderProduct = $opr->findOneBy(['product' => $recommendationProducts->getProduct(), 'orders' => $order ]);
                    $orderProduct->setQuantity( $orderProduct->getQuantity() + $recommendationProducts->getQuantity() );
                    $this->em->flush();
                }

                // Add product to array
                array_push( $products, $recommendationProducts->getProduct() );
            }

            // Alert
            $this->addFlash('success', 'Nouveau panier temporaire créé avec succès');
        } else {
            $order = $this->container->get('session')->get('currentOrder');

            // Add id to session
            $this->container->get('session')->set('currentOrder', $order);

            $orderProducts = $opr->findBy(['orders' => $order]);

            $products = [];
            foreach( $recommendation->getRecommendationProducts() as $recommendationProduct ) {
                if ( !in_array($recommendationProduct->getProduct(), $products) ) {
                    if ( $orderProducts != null ) {
                        foreach ( $orderProducts as $orderProduct ) {
                            if ( $orderProduct->getProduct() === $recommendationProduct->getProduct() ) {
                                // Update Quantity
                                $orderProduct->setQuantity( $orderProduct->getQuantity() + $recommendationProduct->getQuantity() );
                                $this->em->flush();
                            }
                        }
                    } else {
                        // Add all products if order not create
                        $orderProduct = new OrdersProduct();
                        $orderProduct->setOrder( $order);
                        $orderProduct->setProduct( $recommendationProduct->getProduct() );
                        $orderProduct->setQuantity( $recommendationProduct->getQuantity() );
                        $orderProduct->setTotalQuantity(0);
                        $orderProduct->setUnitPrice($recommendationProduct->getProduct()->getPrice() ? $recommendationProduct->getProduct()->getPrice() : 0 );
                        $this->em->merge($orderProduct);
                        $this->em->flush();
                    }
                }

                array_push( $products, $recommendationProduct->getProduct() );
            }

            // Check if product is not duplicate
            $orderProductsArray = $opr->findByToArray( $order );
            $products = [];
            foreach( $recommendation->getRecommendationProducts() as $recommendationProduct ) {
                $ids = array_column($orderProductsArray, 'id', 'id');
                if ( !in_array($recommendationProduct->getProduct(), $products) ) {
                    if (!isset($ids[$recommendationProduct->getProduct()->getId()])) {
                        $orderProduct = new OrdersProduct();
                        $orderProduct->setOrder( $order);
                        $orderProduct->setProduct( $recommendationProduct->getProduct() );
                        $orderProduct->setQuantity( $recommendationProduct->getQuantity() );
                        $orderProduct->setTotalQuantity(0);
                        $orderProduct->setUnitPrice($recommendationProduct->getProduct()->getPrice() ? $recommendationProduct->getProduct()->getPrice() : 0 );
                        $this->em->merge($orderProduct);
                        $this->em->flush();
                    }
                } else {
                    $orderProduct = $opr->findOneBy( ['orders' => $order, 'product' => $recommendationProduct->getProduct()]);
                    $orderProduct->setQuantity( $orderProduct->getQuantity() + $recommendationProduct->getQuantity() );
                    $this->em->flush();
                }

                array_push( $products, $recommendationProduct->getProduct() );
            }

            // Alert
            $this->addFlash('success', 'Ajout avec succès');
        }


        //Return to summary
        return $this->redirectToRoute('recommendation_summary' , ['id' => $recommendation->getId(), 'added' => 'true']);
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("/management/order/product/edit", name="management_order_product_edit")
     * @param Request $request
     * @param OrdersProductRepository $cr
     * @return JsonResponse
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

            if ( $orderProduct->getProduct() != null ) {
                $total = ( $orderProduct->getUnitPrice() * $orderProduct->getProduct()->getRpd() ) + $orderProduct->getTotalQuantity() ;
            } else {
                $total = $orderProduct->getUnitPrice() + $orderProduct->getTotalQuantity() ;
            }
            return new JsonResponse(["type" => 'success', "total" => $total], 200);
        }
        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error',
            404
        ]);
    }

    /**
     * @Route("/management/order/product/delete/{id}", name="management_order_product_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     * @param OrdersProduct $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteProduct(OrdersProduct $product, Request $request ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('deleteOrderArticle' . $product->getId(), $request->get('_token'))) {
            $this->em->remove( $product );
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
        }
        return $this->redirectToRoute('management_order_show', ['id_number' => $product->getOrder()->getIdNumber()]);
    }

    /**
     * @Route("/management/order/save/{id}", name="management_order_save", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
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

        return $this->redirectToRoute('management_order_show', ['id_number' => $order->getIdNumber()]);
    }

    /**
     * @Route("/management/order/valid/{id}", name="management_order_valid", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @param AsyncMethodService $asyncMethodService
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function valid(
        Orders $order,
        AsyncMethodService $asyncMethodService,
        Request $request
    ): Response
    {
        //Security
        if ( $order->getStatus() == 1 ) {
            // Create Signature
            $signature = new Signature();
            $signature->setOrder( $order );
            $this->em->persist( $signature );

            $newDate = new \DateTime( $request->request->get('date-order') );

            // Update status
            $order->setStatus( 2 );
            $order->setCreateDate( $newDate );

            // Generate OTP
            $codeOtp = new SignatureOtp();
            $codeOtp->setSignature( $signature );

            // Save to DB
            $this->em->persist( $codeOtp );

            // Send Sign Email
            $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $order->getCustomer()->getId(),
                'params' => [
                    'subject' => 'Signature électronique de votre devis - LMS-Sodepac',
                    'title' => 'Votre devis vous attend',
                    'text1' => '
                    Veuillez trouver ci-joint le lien vous permettant de signer électroniquement votre commande envoyé par la société Sodepac, 
                    votre signature électronique actera la validation de le commande ci-joint.',
                    'text2' => 'Veuillez utiliser le code suivant pour valider votre signature: '. $codeOtp->getCode(),
                    'link' => $this->generateUrl('signature_order_sign', ['token' => $signature->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'btn_text' => 'Découvrir votre devis'
                ]
            ]);

            $this->em->flush();
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
        }

        return $this->redirectToRoute('management_order_show', ['id_number' => $order->getIdNumber()]);
    }

    /**
     * @Route("/management/order/synthesis", name="management_order_synthesis", methods={"GET", "POST"})
     * @param OrdersRepository $op
     * @return Response
     */
    public function synthesis( OrdersRepository $op ): Response
    {
        if ($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $orders = $op->findByTechnician( $this->getUser() );
        } else {
            $orders = $op->findByAdmin();
        }

        return $this->render('management/order/synthesis/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/management/order/add-product-other", name="management_order_product_other_add", methods={"GET", "POST"})
     * @param Request $request
     * @param OrdersRepository $or
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addOtherProduct( Request $request, OrdersRepository $or ): Response
    {
        $form = $this->createForm( OrdersAddProductType::class);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            if ( $request->query->get('orderNumber') ) {
                $order = $or->findOneBy( ['id_number' => $request->query->get('orderNumber') ]);
            } else {
                $order = $this->container->get('session')->get('currentOrder');
            }
            // Add  product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $order );
            $orderProduct->setProduct( $form->get('product')->getData() );
            $orderProduct->setTotalQuantity( 0 );
            $orderProduct->setQuantity( 0 );

            //Parent product
            if ( $form->get('product')->getData()->getParentProduct() ) {
                $price = $form->get('product')->getData()->getParentProduct()->getPrice();
            } elseif ( $form->get('product')->getData()->getPrice() != NULL ) {
                $price = $form->get('product')->getData()->getPrice();
            } else {
                $price = 0;
            }

            $orderProduct->setUnitPrice( $price );

            $this->em->merge( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $form->get('product')->getData()->getName() .' a été ajouté a la commande.');
            return $this->redirectToRoute('management_order_show' , ['id_number' => $order->getIdNumber()]);
        }

        return $this->render('management/order/add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("management/order/add-product-various", name="order_product_other_various_add", methods={"GET", "POST"})
     * @param Request $request
     * @param OrdersRepository $or
     * @return Response
     */
    /* DISABLE 3/12/2021 BY SKYREKA
    public function addVariousProduct( Request $request, OrdersRepository $or ): Response
    {
        $form = $this->createForm( OrdersAddProductVariousType::class);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            if ( $request->query->get('orderNumber') ) {
                $order = $or->findOneBy( ['id_number' => $request->query->get('orderNumber') ]);
            } else {
                $order = $this->container->get('session')->get('currentOrder');
            }
            // Add  product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $order );
            $orderProduct->setProduct( $form->get('product')->getData() );
            $orderProduct->setTotalQuantity( 0 );
            $orderProduct->setUnitPrice( 0 );
            $orderProduct->setQuantity( 0 );
            $orderProduct->setUnitPrice( $form->get('product')->getData()->getPrice() ? $form->get('product')->getData()->getPrice() : 0 );

            $this->em->merge( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $form->get('product')->getData()->getName() .' a été ajouté a la commande.');
            return $this->redirectToRoute('management_order_show' , ['id_number' => $order->getIdNumber()]);
        }

        return $this->render('management/order/add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }*/

    /**
     * @Route("management/order/add-product-other-field", name="management_order_product_other_field_add", methods={"GET", "POST"})
     * @param Request $request
     * @param OrdersRepository $or
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addOtherFieldProduct( Request $request, OrdersRepository $or ): Response
    {
        $form = $this->createForm( OrdersAddProductFieldType::class);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            if ( $request->query->get('orderNumber') ) {
                $order = $or->findOneBy( ['id_number' => $request->query->get('orderNumber') ]);
            } else {
                $order = $this->container->get('session')->get('currentOrder');
            }
            // Add  product
            $orderProduct = new OrdersProduct();
            $orderProduct->setOrder( $order );
            $orderProduct->setProductName( $form->get('product')->getData() );
            $orderProduct->setTotalQuantity( 0 );
            $orderProduct->setUnitPrice( 0 );
            $orderProduct->setQuantity( 0 );

            $this->em->merge( $orderProduct );

            $this->em->flush();

            // Alert
            $this->addFlash('info', 'Le produit '. $form->get('product')->getData() .' a été ajouté a la commande.');
            return $this->redirectToRoute('management_order_show' , ['id_number' => $order->getIdNumber()]);
        }

        return $this->render('management/order/add_product_field.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/management/select_product_data", name="_management_select_product_data")
     * @param Request $request
     * @param ProductsRepository $pr
     * @return JsonResponse
     */
    public function selectProductData( Request $request, ProductsRepository $pr ): JsonResponse
    {
        //Get information from ajax call
        $term = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        $products = $pr->createQueryBuilder('u')
            ->where('u.name LIKE :name')
            ->setParameter('name', '%' . $term . '%')
            ->setMaxResults( $limit )
            ->andWhere('u.isActive = 1')
            ->getQuery()
            ->getResult()
        ;

        // Return Array of key = id && text = value
        $array = [];
        foreach ($products as $product) {
            $array[] = array(
                'id' => $product->getId(),
                'text' => $product->getName()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }

    /**
     * @Route("/management/order_select_product_various_data", name="_management_select_product_various_data")
     * @param Request $request
     * @param ProductsRepository $pr
     * @return JsonResponse
     */
    public function addProductVariousSelectData( Request $request, ProductsRepository $pr ): JsonResponse
    {
        //Get information from ajax call
        $term = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        $products = $pr->createQueryBuilder('u')
            ->where('u.name LIKE :name')
            ->andWhere('u.category = :category')
            ->setParameter('name', '%' . $term . '%')
            ->setParameter('category', '2')
            ->andWhere('u.isActive = 1')
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult()
        ;

        // Return Array of key = id && text = value
        $array = [];
        foreach ($products as $product) {
            $array[] = array(
                'id' => $product->getId(),
                'text' => $product->getName()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }

    /**
     * @Route("/management/order/swipe/{article}/{product}", name="management_order_article_swipe", methods={"UPDATE"}, requirements={"id":"\d+"})
     * @param OrdersProduct $article
     * @param Products $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProduct(OrdersProduct $article, Products $product, Request $request ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('order_product_swipe_' . $article->getId(), $request->get('_token'))) {
            $article->setProduct( $product );
            $article->setUnitPrice( $product->getPrice() );
            $this->em->flush();
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
        }
        return $this->redirectToRoute('management_order_show', ['id_number' => $article->getOrder()->getIdNumber() ]);
    }

    /*********
     * USER SECTIONS
     *********/

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
     * @Route("/order/sign/{id}", name="user_order_sign", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Orders $order
     * @param MailerInterface $mailer
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sign(
        Orders $order,
        MailerInterface $mailer,
        Request $request
    ): Response
    {
        //Security
        if( $order->getCustomer() != $this->getUser() AND $order->getCreator() != $this->getUser() AND $request->get('print') == false AND $this->getUser()->getStatus() != 'ROLE_ADMIN') {
            throw $this->createNotFoundException('Vous n\'avez pas la permission de signer ce document.');
        }

        if ( $order->getStatus() == 2 ) {
            // Update status
            $order->setStatus( 3 );

            // Send to depot
            // Send to Warehouse
            $message = (new TemplatedEmail())
                ->subject( 'Nouvelle commande de ' . $order->getCreator()->getIdentity() )
                ->from( new Address('noreply@sodepac.fr', 'LMS-Sodepac'))
                ->to( $order->getCustomer()->getWarehouse()->getEmail() )
                ->htmlTemplate( 'emails/notification/user/email_notification.html.twig' )
                ->context([
                    'title' => 'Nouvelle commande venant de '. $order->getCreator()->getIdentity() .' ( CONFIDENTIEL ! )',
                    'text1' => 'ID COMMANDE #'. $order->getIdNumber(),
                    'text2' => 'Destinataire '. $order->getCustomer()->getIdentity(),
                    'link' => $this->generateUrl('order_pdf_view', ['id_number' => $order->getIdNumber(), 'print' => 'true'], UrlGeneratorInterface::ABSOLUTE_URL),
                    'btn_text' => 'Découvrir la commande'
                ])
            ;
            try {
                $mailer->send($message);
            } catch (TransportExceptionInterface $e ) {
                return $e;
            }

            $this->em->flush();

            // Msg
            $this->addFlash('success', 'Commande validée avec succès.');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue.');
        }

        return $this->redirectToRoute('user_order_index');
    }
}

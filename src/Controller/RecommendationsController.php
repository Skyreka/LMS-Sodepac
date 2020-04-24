<?php

namespace App\Controller;

use App\Entity\IndexCultures;
use App\Entity\Products;
use App\Entity\RecommendationProducts;
use App\Entity\Recommendations;
use App\Form\RecommendationAddProductType;
use App\Form\RecommendationAddType;
use App\Repository\CulturesRepository;
use App\Repository\ProductsRepository;
use App\Repository\RecommendationProductsRepository;
use App\Repository\RecommendationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Class RecommendationsController
 * @package App\Controller
 */
class RecommendationsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RecommendationProductsRepository
     */
    private $rpr;

    /**
     * RecommendationsController constructor.
     * @param EntityManagerInterface $em
     * @param RecommendationProductsRepository $rpr
     */
    public function __construct(EntityManagerInterface $em, RecommendationProductsRepository $rpr)
    {
        $this->em = $em;
        $this->rpr = $rpr;
    }

    /**
     * @Route("recommendations/select", name="recommendations.select")
     * @param Request $request
     * @return Response
     */
    public function select( Request $request ): Response
    {
        $recommendation = new Recommendations();
        $form = $this->createForm( RecommendationAddType::class, $recommendation, [ 'user' => $this->getUser() ]);
        $form->handleRequest( $request );


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->all();
            $customer = $data['exploitation']->getData();
            $recommendation->setExploitation( $customer->getExploitation() );
            $this->em->persist( $recommendation );
            $this->em->flush();
            return $this->redirectToRoute('recommendations.canevas', [
                'recommendations' => $recommendation->getId(),
                'slug' => $recommendation->getCulture()->getSlug()
            ]);
        }

        return $this->render('recommendations/select.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Display canevas by id of culture
     * @Route("recommendations/{recommendations}/canevas/{slug}", name="recommendations.canevas")
     * @param Recommendations $recommendations
     * @param IndexCultures $indexCultures
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function canevas( Recommendations $recommendations, IndexCultures $indexCultures, CulturesRepository $cr ): Response
    {
        if ( $this->get('twig')->getLoader()->exists( 'recommendations/canevas/'.$indexCultures->getSlug().'.html.twig' ) ) {
            $totalSize = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );
            return $this->render('recommendations/canevas/'.$indexCultures->getSlug().'.html.twig', [
                'recommendations' => $recommendations,
                'totalSize' => $totalSize,
                'culture' => $indexCultures
            ]);
        } else {
            $this->addFlash('error', 'Aucun canevas existant pour cette culture');
            return $this->redirectToRoute('recommendations.index' );
        }
    }

    /**
     * Add product by click button on canevas
     * @Route("recommendations/add-product", name="recommendations.add.product", methods={"POST"})
     * @param Request $request
     * @param ProductsRepository $pr
     * @param RecommendationsRepository $rr
     * @param CulturesRepository $cr
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function canevasAddProduct( Request $request, ProductsRepository $pr, RecommendationsRepository $rr, CulturesRepository $cr )
    {
        if ($request->isXmlHttpRequest()) {
            $product = $pr->findProductBySlug( $request->get('product_slug') );
            $recommendation = $rr->find( $request->get('recommendation_id'));
            $cultureTotal = $cr->countSizeByIndexCulture( $recommendation->getCulture(), $recommendation->getExploitation() );
            //-- SETTERS
            $recommendationProducts = new RecommendationProducts();
            $recommendationProducts->setProduct( $product );
            $recommendationProducts->setRecommendation( $recommendation );
            $recommendationProducts->setDose( $request->get('dose') );
            $recommendationProducts->setUnit( $request->get('unit') );
            $result = $cultureTotal * $recommendationProducts->getDose();
            $recommendationProducts->setQuantity( $result );

            //-- Go to db new entry
            $this->em->persist($recommendationProducts);
            $this->em->flush();

            return $this->json([
                'name_product' => $recommendationProducts->getProduct()->getName(),
                'dose' => $recommendationProducts->getDose(),
                'unit' => $recommendationProducts->getUnit()
            ], 200);
        }

        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error'
        ]);
    }

    /**
     * @Route("recommendations/{id}/add-other-product", name="recommendations.add.other.product")
     * @param Recommendations $recommendations
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function addOtherProduct( Recommendations $recommendations, Request $request, CulturesRepository $cr )
    {
        $recommendationProducts = new RecommendationProducts();
        $form = $this->createForm( RecommendationAddProductType::class, $recommendationProducts);
        $form->handleRequest( $request );

        $totalSize = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $recommendationProducts->setRecommendation( $recommendations );
            $this->em->persist( $recommendationProducts );
            $this->em->flush();
            $this->addFlash('success', 'Produit ' . $recommendationProducts->getProduct()->getName() . ' ajouté avec succès');
            return $this->redirectToRoute('recommendations.synthese.products', ['id' => $recommendations->getId()]);
        }

        return $this->render('recommendations/addProduct.html.twig', [
            'recommendations' => $recommendations,
            'form' => $form->createView(),
            'totalSize' => $totalSize
        ]);
    }

    /**
     * @Route("recommendations/product/{id}/delete", name="recommendations.delete.product", methods={"DELETE"})
     * @param RecommendationProducts $product
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteProduct( RecommendationProducts $product, Request $request )
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {
            $this->em->remove( $product );
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('recommendations.synthese.products', ['id' => $product->getRecommendation()->getId()]);
    }

    /**
     * @Route("recommendations/{id}/delete", name="recommendations.delete", methods={"DELETE"})
     * @param Recommendations $recommendation
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete( Recommendations $recommendation, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $recommendation->getId(), $request->get('_token' ))) {
            $recommendationProducts = new RecommendationProducts();
            $recommendation->removeRecommendationProduct( $recommendationProducts );
            $this->em->remove( $recommendation );
            $this->em->flush();
            $this->addFlash('success', 'Recommendation supprimé avec succès');
        }
        return $this->redirectToRoute('recommendations.index');
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("recommendations/edit-dose", name="recommendations.edit.dose")
     * @param Request $request
     * @param CulturesRepository $cr
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editDose( Request $request, CulturesRepository $cr )
    {
        if ($request->isXmlHttpRequest()) {
            $recommendation = $this->rpr->find( $request->get('id'));
            $cultureTotal = $cr->countSizeByIndexCulture( $recommendation->getRecommendation()->getCulture(), $recommendation->getRecommendation()->getExploitation() );
            $recommendation->setDose( $request->get('dose'));
            //-- Calcul total of quantity with new dose
            $result = $cultureTotal * $recommendation->getDose();
            $recommendation->setQuantity( $result );
            $this->em->flush();
            return $this->json(["type" => 'success'], 200);
        }
        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error'
        ]);
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("recommendations/edit-total-quantity", name="recommendations.edit.totalQuantity")
     * @param Request $request
     * @return JsonResponse
     */
    public function editQuantity( Request $request )
    {
        if ($request->isXmlHttpRequest()) {
            $recommendation = $this->rpr->find( $request->get('id'));
            $recommendation->setQuantity( $request->get('quantity'));
            $this->em->flush();
            return $this->json(["type" => 'success'], 200);
        }
        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error'
        ]);
    }

    /**
     * @Route("recommendations/{id}/list-products", name="recommendations.synthese.products")
     * @param Recommendations $recommendations
     * @return Response
     */
    public function syntheseProducts( Recommendations $recommendations )
    {
        $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
        return $this->render( 'recommendations/listProducts.html.twig', [
            'recommendations' => $recommendations,
            'products' => $products
        ]);
    }

    /**
     * @Route("recommendations/{id}/synthese", name="recommendations.synthese")
     * @param Recommendations $recommendations
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function synthese( Recommendations $recommendations, CulturesRepository $cr ): Response
    {
        $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
        $cultureTotal = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );

        //-- Update Status of recommendation
        $recommendations->setStatus( 1 );
        $this->em->flush();

        return $this->render('recommendations/synthese.html.twig', [
            'recommendations' => $recommendations,
            'products' => $products,
            'culture' => $recommendations->getCulture(),
            'customer' => $recommendations->getExploitation()->getUsers(),
            'cultureTotal' => $cultureTotal
        ]);
    }

    /**
     * @Route("recommendations/{id}/send", name="recommendations.send", methods="SEND")
     * @param Recommendations $recommendations
     * @param Request $request
     * @param CulturesRepository $cr
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function send( Recommendations $recommendations, Request $request, CulturesRepository $cr, \Swift_Mailer $mailer )
    {
        if ($this->isCsrfTokenValid('send', $request->get('_token'))) {
            //-- Update Status of recommendation
            $recommendations->setStatus( 2 );

            //-- Init @Var
            $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
            $cultureTotal = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );
            $customer = $recommendations->getExploitation()->getUsers();
            $fileName = 'Recommendation-'.$recommendations->getCulture()->getName().'-'.date('y-m-d').'-'.$customer->getId().'.pdf';

            //-- Generate PDF
            $pdfOptions = new Options();
            $pdfOptions->set( 'defaultFront', 'Arial');
            $pdfOptions->set( 'isRemoteEnabled', true);
            $pdf = new Dompdf( $pdfOptions );
            $html = $this->render('recommendations/synthesePdf.html.twig', [
                'products' => $products,
                'recommendations' => $recommendations,
                'customer' => $customer,
                'cultureTotal' => $cultureTotal
            ]);
            $pdf->loadHtml( $html->getContent() );
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            $output = $pdf->output();
            //-- Put file on folder
            file_put_contents('uploads/recommendations/'.$fileName, $output);

            //-- SEND PDF TO USER
            $message = (new \Swift_Message('Nouvelle recommendation disponible'))
                ->setFrom('send@lms-sodepac.fr')
                ->setTo( $customer->getEmail() )
                ->setBody(
                    $this->renderView(
                        'emails/recommendation.html.twig', [
                            'identity' => $customer->getIdentity()
                        ]
                    ),
                    'text/html'
                )
                ->attach( \Swift_Attachment::fromPath( 'uploads/recommendations/'.$fileName ) )
            ;
            $mailer->send($message);

            // Delete File
            /*
            $fileSystem = new Filesystem();
            $fileSystem->remove(['uploads/recommendations/'.$fileName]);
            */

            $this->em->flush();
            $this->addFlash('success', 'Recommandation envoyé avec succès');
        }
        return $this->redirectToRoute('recommendations.index');
    }

    /**
     * @Route("recommendations/{id}/download", name="recommendations.download", methods="DOWNLOAD")
     * @param Recommendations $recommendations
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function download( Recommendations $recommendations, Request $request, CulturesRepository $cr )
    {
        if ($this->isCsrfTokenValid('download', $request->get('_token'))) {
            //-- Init @Var
            $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
            $cultureTotal = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );
            $customer = $recommendations->getExploitation()->getUsers();
            $fileName = 'Recommendation-'.$recommendations->getCulture()->getName().'-'.date('y-m-d').'-'.$customer->getId().'.pdf';

            //-- Generate PDF
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFront', 'Arial');
            $pdf = new Dompdf( $pdfOptions );
            $html = $this->render('recommendations/synthesePdf.html.twig', [
                'products' => $products,
                'recommendations' => $recommendations,
                'customer' => $customer,
                'cultureTotal' => $cultureTotal
            ]);
            $pdf->loadHtml( $html->getContent() );
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            $pdf->stream( $fileName );
        }
        return $this->redirectToRoute('recommendations.index');
    }

    /**
     * @Route("recommendations", name="recommendations.index")
     * @param RecommendationsRepository $rr
     * @return Response
     */
    public function indexStaff( RecommendationsRepository $rr ): Response
    {
        $year = date('Y');
        if ( $this->getUser()->getStatus() === 'ROLE_TECHNICIAN') {
            return $this->render('recommendations/index.html.twig', [
                'recommendations' => $rr->findByExploitationOfTechnicianAndYear( $this->getUser(), $year )
            ]);
        } elseif ($this->getUser()->getStatus() === 'ROLE_ADMIN') {
            return $this->render('recommendations/index.html.twig', [
                'recommendations' => $rr->findAll()
            ]);
        } else {
            return $this->render('recommendations/index.html.twig', [
                'recommendations' => $rr->findAllByYear($year)
            ]);
        }
    }

    /**
     * @Route("recommendations/data/{year}", name="recommendations.index.data")
     * @param RecommendationsRepository $rr
     * @param $year
     * @return Response
     */
    public function indexStaffData( RecommendationsRepository $rr, $year ): Response
    {
        //-- If user is technician get recommendation of user of technician
        if ( $this->getUser()->getStatus() === 'ROLE_TECHNICIAN') {
            return $this->render('recommendations/data.html.twig', [
                'recommendations' => $rr->findByExploitationOfTechnicianAndYear( $this->getUser(), $year )
            ]);
        } elseif ($this->getUser()->getStatus() === 'ROLE_ADMIN') {
            return $this->render('recommendations/data.html.twig', [
                'recommendations' => $rr->findAll()
            ]);
        } else {
            return $this->render('recommendations/data.html.twig', [
                'recommendations' => $rr->findAllByYear($year)
            ]);
        }
    }

    /**
 * @Route("exploitation/recommendations", name="exploitation.recommendations.index")
 * @param RecommendationsRepository $rr
 * @return Response
 */
    public function indexUser( RecommendationsRepository $rr ): Response
    {
        $year = date('Y');
        return $this->render('exploitation/recommendations/index.html.twig', [
            'recommendations' => $rr->findByExploitationOfCustomerAndYear( $this->getUser()->getId(), $year )
        ]);
    }

    /**
     * @Route("exploitation/recommendations/data/{year}", name="exploitation.recommendations.data")
     * @param RecommendationsRepository $rr
     * @param $year
     * @return Response
     */
    public function dataUser( RecommendationsRepository $rr, $year ): Response
    {
        return $this->render('exploitation/recommendations/data.html.twig', [
            'recommendations' => $rr->findByExploitationOfCustomerAndYear( $this->getUser()->getId(), $year )
        ]);
    }

    /**
     * @Route("exploitation/recommendations/{id}", name="exploitation.recommendations.show")
     * @param Recommendations $recommendations
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function showUser( Recommendations $recommendations, CulturesRepository $cr ): Response
    {
        $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
        $cultureTotal = $cr->countSizeByIndexCulture( $recommendations->getCulture(), $recommendations->getExploitation() );
        return $this->render('exploitation/recommendations/show.html.twig', [
            'recommendations' => $recommendations,
            'products' => $products,
            'culture' => $recommendations->getCulture(),
            'customer' => $recommendations->getExploitation()->getUsers(),
            'cultureTotal' => $cultureTotal
        ]);
    }
}
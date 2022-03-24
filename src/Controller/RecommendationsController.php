<?php

namespace App\Controller;

use App\AsyncMethodService;
use App\Entity\Exploitation;
use App\Entity\IndexCanevas;
use App\Entity\IndexCultures;

use App\Entity\RecommendationProducts;
use App\Entity\Recommendations;
use App\Entity\Stocks;
use App\Entity\Users;
use App\Form\RecommendationAddProductType;
use App\Form\RecommendationAddType;
use App\Form\RecommendationMentionsType;
use App\Repository\CulturesRepository;
use App\Repository\ProductsRepository;
use App\Repository\RecommendationProductsRepository;
use App\Repository\RecommendationsRepository;
use App\Repository\StocksRepository;
use App\Repository\UsersRepository;
use App\Service\EmailNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Dompdf\Exception;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Validator\Constraints\Json;

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
     * @Route("recommendations", name="recommendation_index", methods={"GET"})
     * @param RecommendationsRepository $rr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function indexStaff( RecommendationsRepository $rr): Response
    {
        // Get Last Recommendations
        if ($this->isGranted('ROLE_ADMIN')) {
            $lastRecommendations = $rr->findAllByYear( date('Y') );
        } else {
            $lastRecommendations = $rr->findByExploitationOfTechnicianAndYear( $this->getUser(), date('Y') );
        }

        return $this->render('recommendations/staff/index.html.twig', [
            'lastRecommendations' => $lastRecommendations
        ]);
    }

    /**
     * @Route("recommendation/new_data_ajax", name="recommendations_select_data")
     * @param Request $request
     * @param UsersRepository $ur
     * @param Users $users
     * @return JsonResponse
     */
    public function selectData(Request $request, UsersRepository $ur): JsonResponse
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
                ->leftJoin( Exploitation::class, 'e', 'WITH', 'e.users = u.id')
                ->andWhere('u.id = e.users')
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
                ->leftJoin( Exploitation::class, 'e', 'WITH', 'e.users = u.id')
                ->andWhere('u.id = e.users')
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
                'id' => $user->getExploitation()->getId(),
                'text' => $user->getIdentity() . '(' . $user->getCompany() . ')'
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }

    /**
     * @Route("recommendations/new", name="recommendation_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new( Request $request ): Response
    {
        $recommendation = new Recommendations();
        $form = $this->createForm( RecommendationAddType::class, $recommendation);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Display error if user don't have exploitation
            if( $data->getExploitation() == NULL) {
                $this->addFlash('danger', 'Votre client n\'a aucune exploitation déclarée, veuillez modifier son compte pour pouvoir lui établir un catalogue');
                return $this->redirectToRoute('recommendation_new');
            }
            $recommendation->setChecked(0);
            $this->em->persist( $recommendation );
            $this->em->flush();

            // Other recommendation ( Go directly on list product page no canevas display)
            if ( $recommendation->getCulture()->getSlug() == 'other') {
                return $this->redirectToRoute('recommendation_product_list', ['id' => $recommendation->getId()]);
            }

            return $this->redirectToRoute('recommendation_canevas', [
                'recommendations' => $recommendation->getId(),
                'slug' => $recommendation->getCulture()->getSlug()
            ]);
        }

        return $this->render('recommendations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Display canevas by id of culture
     * @Route("recommendations/{recommendations}/canevas/{slug}", name="recommendation_canevas", methods={"GET", "POST"}, requirements={"recommendations":"\d+"})
     * @param Recommendations $recommendations
     * @param IndexCanevas $indexCanevas
     * @param CulturesRepository $cr
     * @param RecommendationProductsRepository $rpr
     * @return Response
     */
    public function canevas( Recommendations $recommendations, IndexCanevas $indexCanevas, CulturesRepository $cr, RecommendationProductsRepository $rpr ): Response
    {
        if ( $this->get('twig')->getLoader()->exists( 'recommendations/canevas/assets/'.$indexCanevas->getSlug().'.html.twig' ) ) {
            return $this->render('recommendations/canevas/assets/'.$indexCanevas->getSlug().'.html.twig', [
                'recommendations' => $recommendations,
                'rpr' => $rpr,
                'culture' => $indexCanevas,
                'printRequest' => false
            ]);
        } else {
            $this->addFlash('danger', 'Aucun canevas existant pour cette culture');
            return $this->redirectToRoute('recommendation_index' );
        }
    }

    /**
     * Add product by click button on canevas
     * @Route("recommendations/canevas/add-product", name="recommendations_canevas_product_add", methods={"GET", "POST"})
     * @param Request $request
     * @param ProductsRepository $pr
     * @param RecommendationsRepository $rr
     * @param CulturesRepository $cr
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function canevasAddProduct( Request $request, ProductsRepository $pr, RecommendationsRepository $rr, RecommendationProductsRepository $rpr )
    {
        if ($request->isXmlHttpRequest()) {
            $product = $pr->findProductBySlug( $request->get( 'product_slug') );
            $recommendation = $rr->find( $request->get('recommendation_id'));
            $cultureTotal = $recommendation->getCultureSize();


            //if ( !$rpr->findOneBy( ['recommendation' => $recommendation, 'product' => $product] )) {
                //-- SETTERS
                $recommendationProducts = new RecommendationProducts();
                $recommendationProducts->setCId( $request->get('c_id') );
                $recommendationProducts->setProduct( $product );
                $recommendationProducts->setRecommendation( $recommendation );
                $recommendationProducts->setDose( $request->get('dose') );
                $recommendationProducts->setUnit( $request->get('unit') );
                $result = $cultureTotal * floor($recommendationProducts->getDose() * 1000) / 1000;
                $recommendationProducts->setQuantity( $result );

                //-- Go to db new entry
                $this->em->persist($recommendationProducts);
                $this->em->flush();
            //}

            return new JsonResponse([
                'name_product' => $recommendationProducts->getProduct()->getName(),
                'dose' => $recommendationProducts->getDose(),
                'unit' => $recommendationProducts->getUnit()
            ], 200);
        }

        return new JsonResponse([
            'message' => 'AJAX Only',
            'type' => 'error'
        ], 404);
    }

    /**
     * @Route("recommendations/{id}/add-other-product", name="recommendation_product_add", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Recommendations $recommendations
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function newOtherProduct( Recommendations $recommendations, Request $request, CulturesRepository $cr )
    {
        $recommendationProducts = new RecommendationProducts();
        $form = $this->createForm( RecommendationAddProductType::class, $recommendationProducts);
        $form->handleRequest( $request );

        $totalSize = $recommendations->getCultureSize();

        if ( $form->isSubmitted() && $form->isValid() ) {
            $dose = $form->get('dose')->getData();

            $recommendationProducts->setRecommendation( $recommendations );
            if($dose !== null) {
            $recommendationProducts->setDose( $dose->getDose() );
            }
            //$recommendationProducts->setUnit( $dose->getUnit() );
            // Auto Calc dose x total size
            $recommendationProducts->setQuantity( $recommendations->getCultureSize() * floor($dose->getDose() * 1000) / 1000 );

            $this->em->persist( $recommendationProducts );
            $this->em->flush();
            $this->addFlash('success', 'Produit ' . $recommendationProducts->getProduct()->getName() . ' ajouté avec succès');
            return $this->redirectToRoute('recommendation_product_list', ['id' => $recommendations->getId()]);
        }

        return $this->render('recommendations/staff/product/new.html.twig', [
            'recommendations' => $recommendations,
            'form' => $form->createView(),
            'totalSize' => $totalSize
        ]);
    }

    /**
     * @Route("recommendations/product/{id}/delete", name="recommendation_product_delete", methods={"DELETE"}, requirements={"id":"\d+"})
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
        return $this->redirectToRoute('recommendation_product_list', ['id' => $product->getRecommendation()->getId()]);
    }

    /**
     * @Route("recommendations/{id}/delete", name="recommendation_delete", methods={"DELETE"}, requirements={"id":"\d+"})
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
            $this->addFlash('success', 'Catalogue supprimé avec succès');
        }
        return $this->redirectToRoute('recommendation_index');
    }

    /**
     * Edit Dose with editable Ajax Table
     * @Route("recommendations/product/edit/dose", name="recommendation_product_dose_edit")
     * @param Request $request
     * @param CulturesRepository $cr
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editDose( Request $request )
    {
        if ($request->isXmlHttpRequest()) {
            $recommendationProduct = $this->rpr->find( $request->get('id'));
            $cultureTotal = $recommendationProduct->getRecommendation()->getCultureSize();
            $recommendationProduct->setDoseEdit( $request->get('dose_edit'));
            //-- Calcul total of quantity with new dose
            if ( $recommendationProduct->getDoseEdit() != null ) {
                $result = $cultureTotal * $recommendationProduct->getDoseEdit();
            } else {
                $result = $cultureTotal * $recommendationProduct->getDose();
            }
            $recommendationProduct->setQuantity( $result );
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
     * @Route("recommendations/{id}/list-products", name="recommendation_product_list", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Recommendations $recommendations
     * @return Response
     */
    public function listProduct( Recommendations $recommendations )
    {
        $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
        return $this->render( 'recommendations/staff/product/list.html.twig', [
            'recommendations' => $recommendations,
            'products' => $products
        ]);
    }

    /**
     * This function is disable 29/12/2020
     * From list product technician go to directly to summary
     * @Route("recommendations/{id}/mentions", name="recommendation_mentions", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Recommendations $recommendations
     * @param Request $request
     * @return Response
     */
    public function mentions( Recommendations $recommendations, Request $request )
    {
        $form = $this->createForm(RecommendationMentionsType::class, $recommendations );
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //Update Status of recommendations
            $recommendations->setStatus( 1 );
            $this->em->flush();

            $this->addFlash('info', 'Le catalogue est maintenant en statut Créé');
            $this->addFlash('success', 'Catalogue créé avec succès');
            return $this->redirectToRoute('recommendation_summary', ['id' => $recommendations->getId()]);
        }

        return $this->render('recommendations/staff/mentions.html.twig', [
            'recommendations' => $recommendations,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recommendations/{id}/summary", name="recommendation_summary")
     * @param Recommendations $recommendation
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function summary( Recommendations $recommendation, CulturesRepository $cr ): Response
    {
        $products = $this->rpr->findBy( ['recommendation' => $recommendation] );
        $cultureTotal = $recommendation->getCultureSize();

        // After modification of 29/12/2020 (Disable mentions) User go to summary directly by list product page
        if ( $recommendation->getStatus() == 0 ) {

            $recommendation->setStatus( 1 );
            $this->em->flush();

        }

        return $this->render('recommendations/staff/summary.html.twig', [
            'recommendation' => $recommendation,
            'products' => $products,
            'culture' => $recommendation->getCulture(),
            'customer' => $recommendation->getExploitation()->getUsers(),
            'cultureTotal' => $cultureTotal
        ]);
    }

    /**
     * @Route("recommendations/{id}/send", name="recommendation_send", methods="SEND")
     * @param Recommendations $recommendations
     * @param Request $request
     * @param StocksRepository $sr
     * @param RecommendationProductsRepository $rpr
     * @param AsyncMethodService $asyncMethodService
     * @return Response
     */
    public function send (
        Recommendations $recommendations,
        Request $request,
        StocksRepository $sr,
        RecommendationProductsRepository $rpr,
        AsyncMethodService $asyncMethodService
    )
    {
        if ($this->isCsrfTokenValid('send', $request->get('_token'))) {
            //-- Update Status of recommendation
            $recommendations->setStatus( 3 );

            //-- Add oldStock
            $oldStocks = $sr->findBy(array('exploitation' => $recommendations->getExploitation()));
            $stockProducts = [];
            foreach ($oldStocks as $oldStock) {
                $stockProducts[] = $oldStock->getProduct()->getId();
            }

            //-- Add to stock
            $products = $rpr->findBy( ['recommendation' => $recommendations ]);
            foreach ( $products as $product ) {
                if (!in_array($product->getProduct()->getId(), $stockProducts)) {
                    $stock = new Stocks();
                    $stock->setExploitation( $recommendations->getExploitation() );
                    $stock->setProduct( $product->getProduct() );

                    //--Add product to stock list
                    $stockProducts[] = $stock->getProduct()->getId();

                    $this->em->persist( $stock );
                }
            }

            // Save to Db
            $this->em->flush();

            //Send Email to user
            $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $recommendations->getExploitation()->getUsers()->getId(),
                'params' => [
                    'subject' => 'Un nouveau catalogue est disponible sur '. $this->getParameter('APP_NAME'),
                    'text1' => 'Un nouveau catalogue est disponible sur votre application.'
                ]
            ]);

            $this->addFlash('success', 'Email envoyé avec succès. Statut du catalogue: Envoyé');
            return $this->redirectToRoute('recommendation_summary', ['id' => $recommendations->getId()]);
        }
        return $this->redirectToRoute('recommendation_index');
    }

    /**
     * @Route("recommendation/{id}/download", name="recommendation_download", methods="DOWNLOAD")
     * @param Recommendations $recommendations
     * @param Request $request
     * @param CulturesRepository $cr
     * @param RecommendationProductsRepository $recommendationProductsRepository
     * @return RedirectResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function download( Recommendations $recommendations, Request $request, CulturesRepository $cr, RecommendationProductsRepository $rpr )
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '-1');
        $csrfToken = $request->get('_token');
        if ($this->isCsrfTokenValid('download', $csrfToken)) {
            try {
                //-- Init @Var
                $token = md5(uniqid(rand()));
                $fileSystem = new Filesystem();
                $fileSystem->mkdir( '../public/uploads/recommendations/process/'.$token );
                $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
                $cultureTotal = $recommendations->getCultureSize();
                $customer = $recommendations->getExploitation()->getUsers();
                $fileName = 'Catalogue-'.$recommendations->getCulture()->getName().'-'.date('y-m-d').'-'.$customer->getId().'.pdf';

                //-- Generate PDF
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFront', 'Tahoma');
                $pdfOptions->setIsRemoteEnabled( true );

                //-- First Page
                $recommendationDoc = new Dompdf( $pdfOptions );
                $html = $this->render('recommendations/synthesePdf.html.twig', [
                    'products' => $products,
                    'recommendations' => $recommendations,
                    'customer' => $customer,
                    'cultureTotal' => $cultureTotal
                ]);
                $recommendationDoc->loadHtml( $html->getContent() );
                $recommendationDoc->setPaper('A4', 'portrait');
                $recommendationDoc->render();
                $outputFirstFile = $recommendationDoc->output();
                file_put_contents( '../public/uploads/recommendations/process/'.$token.'/1.pdf', $outputFirstFile);

                //-- Canevas
                // Only if culture is not other
                if ( $recommendations->getCulture()->getSlug() != 'other') {
                    $canevasPage = new Dompdf( $pdfOptions );
                    $cIdArray = $rpr->findCId( $recommendations );
                    $html =  $this->render('recommendations/canevas/assets/'.$recommendations->getCulture()->getSlug().'.html.twig', [
                        'recommendations' => $recommendations,
                        'totalSize' => 0,
                        'culture' => $recommendations->getCulture(),
                        'printRequest' => true,
                        'c_id_array' => $cIdArray
                    ]);
                    set_time_limit(1000);
                    ini_set('max_execution_time', 300);
                    ini_set('memory_limit', '-1');
                    $canevasPage->loadHtml( $html->getContent() );
                    $canevasPage->setPaper('A1', 'landscape');
                    $canevasPage->render();
                    $outputFirstFile = $canevasPage->output();
                    file_put_contents( '../public/uploads/recommendations/process/'.$token.'/2.pdf', $outputFirstFile);
                }

                //-- Merge All Documents
                $merger = new Merger();
                $merger->addFile( '../public/uploads/recommendations/process/'.$token.'/1.pdf' );



                if ( $recommendations->getCulture()->getSlug() != 'other') {
                    $merger->addFile('../public/uploads/recommendations/process/' . $token . '/2.pdf');
                };

                if ( $recommendations->getMention() != NULL OR $recommendations->getMentionTxt() != NULL ) {
                    $merger->addFile('../public/mentions/' . $recommendations->getMention() . '.pdf');
                }

                $finalDocument = $merger->merge();
                //-- Finale File
                file_put_contents( '../public/uploads/recommendations/'.$token.'.pdf', $finalDocument);

                // Save file to DB
                $recommendations
                    ->setPdf( $token . '.pdf' )
                    ->setStatus(2);

                $this->em->flush();

                //-- Download for user
                //$this->forceDownLoad( '../public/uploads/recommendations/'.$token.'.pdf', $fileName );

                //-- Remove temp folder
                $fileSystem->remove('../public/uploads/recommendations/process/'.$token);

                $this->addFlash('success', 'Document généré avec succès. Statut du catalogue: Généré');
                return $this->redirectToRoute('recommendation_summary', ['id' => $recommendations->getId()]);
            } catch (Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue');
                return $this->redirectToRoute('recommendation_summary', ['id' => $recommendations->getId()]);
            }
        }
    }

    private function forceDownLoad($filename, $nameOfFile)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".basename($nameOfFile).";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename));
        @readfile($filename);
    }

    /**
     * @Route("exploitation/recommendations", name="exploitation_recommendation_index")
     * @param RecommendationsRepository $rr
     * @return Response
     */
    public function indexUser( RecommendationsRepository $rr ): Response
    {
        return $this->render('exploitation/recommendations/index.html.twig', [
            'recommendations' => $rr->findByExploitationOfCustomerAndYear( $this->getUser(), date('Y') )
        ]);
    }

    /**
     * @Route("exploitation/recommendations/data/{year}", name="exploitation_recommendation_data")
     * @param RecommendationsRepository $rr
     * @param $year
     * @return Response
     */
    public function dataUser( RecommendationsRepository $rr, $year ): Response
    {
        return $this->render('exploitation/recommendations/data.html.twig', [
            'recommendations' => $rr->findByExploitationOfCustomerAndYear( $this->getUser()->getId(), $year ),
            'year' => $year
        ]);
    }

    /**
     * @Route("exploitation/recommendations/{id}", name="exploitation_recommendation_show")
     * @param Recommendations $recommendations
     * @param CulturesRepository $cr
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function showUser( Recommendations $recommendations, CulturesRepository $cr ): Response
    {
        if ($this->getUser() != $recommendations->getExploitation()->getUsers()) {
            throw $this->createNotFoundException('Cette recommendation ne vous appartient pas.');
        }
        $recommendations->setChecked(1);
        $this->em->flush();
        $products = $this->rpr->findBy( ['recommendation' => $recommendations] );
        $cultureTotal = $recommendations->getCultureSize();
        return $this->render('exploitation/recommendations/show.html.twig', [
            'recommendations' => $recommendations,
            'products' => $products,
            'culture' => $recommendations->getCulture(),
            'customer' => $recommendations->getExploitation()->getUsers(),
            'cultureTotal' => $cultureTotal
        ]);
    }

    /**
     * @Route("exploitation/recommendations/{id}/pdf", name="exploitation_recommendation_show_pdf")
     * @param Recommendations $recommendations
     * @return Response
     */
    public function showPdfUser( Recommendations $recommendations ): Response
    {
        if ($this->getUser() != $recommendations->getExploitation()->getUsers()) {
            throw $this->createNotFoundException('Cette recommendation ne vous appartient pas.');
        }
        return $this->render('exploitation/recommendations/show_pdf.html.twig', [
            'recommendations' => $recommendations
        ]);
    }
}

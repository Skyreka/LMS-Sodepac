<?php

namespace App\Http\Controller\Technician;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Catalogue\CatalogueService;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Entity\CatalogueProducts;
use App\Domain\Catalogue\Form\CatalogueType;
use App\Domain\Catalogue\Repository\CanevasProductRepository;
use App\Domain\Catalogue\Repository\CatalogueProductsRepository;
use App\Domain\Catalogue\Repository\CatalogueRepository;
use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Recommendation\Entity\Recommendations;
use App\Domain\Catalogue\Event\CatalogueValidatedEvent;
use App\Domain\Recommendation\Repository\RecommendationProductsRepository;
use App\Domain\Stock\Entity\Stocks;
use App\Domain\Stock\Repository\StocksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Dompdf\Options;
use iio\libmergepdf\Merger;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/technician/catalogue", name="catalogue_")
 */
class CatalogueController extends AbstractController
{
    public function __construct(
        private readonly CatalogueService $catalogueService,
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('technician/catalogue/index.html.twig', [
            'catalogues' => $this->catalogueService->getCataloguesForStaff()
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $catalogue = new Catalogue();
        $form           = $this->createForm(CatalogueType::class, $catalogue);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // TODO: To check and test
            if($catalogue->getCanevas()->getSlug() == 'other') {
                return $this->redirectToRoute('recommendation_product_list', ['id' => $catalogue->getId()]);
            }

            $this->catalogueService->create($catalogue);

            return $this->redirectToRoute('catalogue_canevas', [
                'catalogue' => $catalogue->getId()
            ]);
        }

        return $this->render('technician/catalogue/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{catalogue}/canevas", name="canevas", methods={"GET", "POST"})
     */
    public function canevas(Catalogue $catalogue): Response
    {
        return $this->render('technician/catalogue/canevas.html.twig', [
            'catalogue' => $catalogue
        ]);
    }

    /**
     * @Route("/new_data_ajax", name="select_user_ajax")
     */
    public function selectUserAjax(Request $request, UsersRepository $ur): JsonResponse
    {
        //Get information from ajax call
        $term  = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        //Query of like call
        if($this->getUser()->getStatus() == 'ROLE_ADMIN') {
            $users = $ur->createQueryBuilder('u')
                ->orWhere('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->orWhere('u.company LIKE :company')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->setParameter('company', '%' . $term . '%')
                ->leftJoin(Exploitation::class, 'e', 'WITH', 'e.users = u.id')
                ->andWhere('u.id = e.users')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        } else {
            // Technician view only them users
            $users = $ur->createQueryBuilder('u')
                ->orWhere('u.lastname LIKE :lastname')
                ->orWhere('u.firstname LIKE :firstname')
                ->orWhere('u.company LIKE :company')
                ->setParameter('lastname', '%' . $term . '%')
                ->setParameter('firstname', '%' . $term . '%')
                ->setParameter('company', '%' . $term . '%')
                ->leftJoin(Exploitation::class, 'e', 'WITH', 'e.users = u.id')
                ->andWhere('u.id = e.users')
                ->andWhere('u.technician = :tech')
                ->setParameter(':tech', $this->getUser())
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }

        // Return Array of key = id && text = value
        $array = [];
        foreach($users as $user) {
            $array[] = array(
                'id' => $user->getId(),
                'text' => $user->getIdentity() . ' ' . $user->getCompany()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse($array, 200);
    }

    /**
     * Add product by click button on canevas
     * @Route("/canevas/add-product", name="canevas_add_product", methods={"GET", "POST"})
     */
    public function canevasAddProduct(
        Request $request,
        CatalogueRepository $cr,
        CanevasProductRepository $cpr
    )
    {
        $canevasProduct = $cpr->findOneBy(['id' => $request->get('btn_id')]);
        $catalogue = $cr->findOneBy(['id' => $request->get('catalogue_id')]);

        $catalogueProduct = new CatalogueProducts();
        $catalogueProduct->setCatalogue($catalogue);
        $catalogueProduct->setProduct($canevasProduct);
        $result = $catalogue->getCultureSize() * floor($canevasProduct->getDose() * 1000) / 1000;
        $catalogueProduct->setQuantity($result);

        $this->em->persist($catalogueProduct);
        $this->em->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/product/list/{id}", name="product_list", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function listProduct(Catalogue $catalogue)
    {
        return $this->render('technician/catalogue/product/list.html.twig', [
            'catalogue' => $catalogue
        ]);
    }

    /**
     * @Route("/catalogue/product/delete/{id}", name="product_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function deleteProduct(CatalogueProducts $catalogueProducts, Request $request)
    {
        if($this->isCsrfTokenValid('delete_catalogue_product_' . $catalogueProducts->getId(), $request->get('_token'))) {
            $this->em->remove($catalogueProducts);
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('catalogue_product_list', ['id' => $catalogueProducts->getCatalogue()->getId()]);
    }

    /**
     * @Route("/product/edit/dose", name="product_dose_edit")
     */
    public function editDose(
        Request $request,
        CatalogueProductsRepository $cpr
    )
    {
        if($request->isXmlHttpRequest()) {
            $catalogueProduct = $cpr->find($request->get('id'));
            $catalogueProduct->setDoseEdit($request->get('dose_edit'));
            //-- Calcul total of quantity with new dose
            if($catalogueProduct->getDoseEdit() != null) {
                $result = $catalogueProduct->getCatalogue()->getCultureSize() * $catalogueProduct->getDoseEdit();
            } else {
                $result = $catalogueProduct->getCatalogue()->getCultureSize() * $catalogueProduct->getDose();
            }
            $catalogueProduct->setQuantity($result);
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
     * @Route("/{id}/summary", name="summary")
     */
    public function summary(Catalogue $catalogue): Response
    {
        return $this->render('technician/catalogue/summary.html.twig', [
            'catalogue' => $catalogue
        ]);
    }

    /**
     * @Route("/{id}/pdf/generate", name="pdf_generate")
     */
    public function pdfGenerate(
        Catalogue $catalogue,
        CatalogueService $catalogueService
    ): RedirectResponse
    {
        if($catalogue->getStatus() == 0) {
            try {
                //-- Init @Var
                $token      = md5(uniqid(rand()));
                $fileSystem = new Filesystem();
                $fileSystem->mkdir('../public/uploads/catalogue/process/' . $token);
                $fileName = 'Catalogue-' . $catalogue->getCanevas()->getName() . '-' . date('y-m-d') . '-' . $catalogue->getCustomer()->getIdentity() . '.pdf';

                //-- Generate PDF
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFront', 'Tahoma');
                $pdfOptions->setIsRemoteEnabled(true);

                //-- First Page
                $recommendationDoc = new Dompdf($pdfOptions);
                $html              = $this->render('technician/catalogue/pdf/first_page.html.twig', [
                    'catalogue' => $catalogue
                ]);
                $recommendationDoc->loadHtml($html->getContent());
                $recommendationDoc->setPaper('A4', 'portrait');
                $recommendationDoc->render();
                $outputFirstFile = $recommendationDoc->output();
                file_put_contents('../public/uploads/catalogue/process/' . $token . '/1.pdf', $outputFirstFile);

                //-- Canevas
                // Only if culture is not other
                if($catalogue->getCanevas()->getSlug() != 'other') {
                    $canevasPage = new Dompdf($pdfOptions);
                    $html        = $this->render('technician/catalogue/pdf/canevas.html.twig', [
                        'catalogue' => $catalogue
                    ]);
                    set_time_limit(1000);
                    ini_set('max_execution_time', 300);
                    ini_set('memory_limit', '-1');
                    $canevasPage->loadHtml($html->getContent());
                    $canevasPage->setPaper('A1', 'landscape');
                    $canevasPage->render();
                    $outputFirstFile = $canevasPage->output();
                    file_put_contents('../public/uploads/catalogue/process/' . $token . '/2.pdf', $outputFirstFile);
                }

                //-- Merge All Documents
                $merger = new Merger();
                $merger->addFile('../public/uploads/catalogue/process/' . $token . '/1.pdf');


                if($catalogue->getCanevas()->getSlug() != 'other') {
                    $merger->addFile('../public/uploads/catalogue/process/' . $token . '/2.pdf');
                }

                $finalDocument = $merger->merge();

                //-- Finale File
                file_put_contents('../public/uploads/catalogue/' . $token . '.pdf', $finalDocument);

                // Save file to DB
                $catalogue
                    ->setPdf($token . '.pdf')
                    ->setStatus(2);

                $this->em->flush();

                //-- Remove temp folder
                $fileSystem->remove('../public/uploads/catalogue/process/' . $token);

                $this->addFlash('success', 'Document généré avec succès. Statut du catalogue: Généré');
                return $this->redirectToRoute('catalogue_summary', ['id' => $catalogue->getId()]);
            } catch(Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue');
                return $this->redirectToRoute('catalogue_summary', ['id' => $catalogue->getId()]);
            }
        } else {
            $this->addFlash('warning', 'PDF déjà généré');
            return $this->redirectToRoute('catalogue_summary', ['id' => $catalogue->getId()]);
        }
    }

    /**
     * @Route("/catalogue/{id}/send", name="send", methods="SEND")
     */
    public function send(
        Catalogue $catalogue,
        Request $request,
        StocksRepository $sr,
        CatalogueProductsRepository $cpr
    )
    {
        if($this->isCsrfTokenValid('send', $request->get('_token'))) {
            //-- Update Status of recommendation
            $catalogue->setStatus(3);

            //-- Add oldStock
            $oldStocks     = $sr->findBy(array('exploitation' => $catalogue->getCustomer()->getExploitation()));
            $stockProducts = [];
            foreach($oldStocks as $oldStock) {
                $stockProducts[] = $oldStock->getProduct()->getId();
            }

            //-- Add to stock
            $products = $cpr->findBy(['catalogue' => $catalogue]);
            foreach($products as $product) {
                if(! in_array($product->getProduct()->getId(), $stockProducts)) {
                    $stock = new Stocks();
                    $stock->setExploitation($catalogue->getCustomer()->getExploitation());
                    $stock->setProduct($product->getProduct()->getProduct());

                    //--Add product to stock list
                    $stockProducts[] = $stock->getProduct()->getId();

                    $this->em->persist($stock);
                }
            }

            // Save to Db
            $this->em->flush();

            $this->dispatcher->dispatch(new CatalogueValidatedEvent($catalogue));

            $this->addFlash('success', 'Email envoyé avec succès. Statut du catalogue: Envoyé');
            return $this->redirectToRoute('catalogue_summary', ['id' => $catalogue->getId()]);
        }
        return $this->redirectToRoute('catalogue_index');
    }
}

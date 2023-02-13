<?php

namespace App\Http\Admin\Controller;


use App\Domain\Catalogue\CanevasService;
use App\Domain\Catalogue\Entity\CanevasDisease;
use App\Domain\Catalogue\Entity\CanevasIndex;
use App\Domain\Catalogue\Entity\CanevasProduct;
use App\Domain\Catalogue\Entity\CanevasStep;
use App\Domain\Catalogue\Form\CanevasDiseaseType;
use App\Domain\Catalogue\Form\CanevasProductEditType;
use App\Domain\Catalogue\Form\CanevasProductType;
use App\Domain\Catalogue\Form\CanevasStepType;
use App\Domain\Product\Repository\ProductsRepository;
use App\Http\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_SUPERADMIN")
 * @Route("/canevas", name="canevas_")
 */
class CanevasController extends AbstractController
{
    public function __construct(
        private readonly CanevasService $canevasService
    )
    {
    }

    /**
     * @Route("/", name="index", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        $canevas = $this->canevasService->getAllCanevas();
        return $this->render('super_admin/canevas/index.html.twig', [
            'canevas' => $canevas
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function canevas(
        CanevasIndex $canevasIndex,
        Request $request
    ): Response
    {
        $canevasDisease = new CanevasDisease();
        $canevasDiseaseForm = $this->createForm(CanevasDiseaseType::class, $canevasDisease);
        $canevasDiseaseForm->handleRequest($request);
        if ($canevasDiseaseForm->isSubmitted() && $canevasDiseaseForm->isValid()) {
            $this->canevasService->createDisease($canevasDisease, $canevasIndex);
        }

        $canevasStep = new CanevasStep();
        $canevasStepForm = $this->createForm(CanevasStepType::class, $canevasStep);
        $canevasStepForm->handleRequest($request);
        if ($canevasStepForm->isSubmitted() && $canevasStepForm->isValid()) {
            $this->canevasService->createStep($canevasStep, $canevasIndex);
        }

        $canevasProduct = new CanevasProduct();
        $canevasAddProductForm = $this->createForm(CanevasProductType::class, $canevasProduct);
        $canevasAddProductForm->handleRequest($request);
        if ($canevasAddProductForm->isSubmitted() && $canevasAddProductForm->isValid()) {
            $this->canevasService->createBouton($canevasProduct, $canevasIndex, $request);
        }

        $canevasEditProductForm = $this->createForm(CanevasProductEditType::class);
        $canevasEditProductForm->handleRequest($request);
        if ($canevasEditProductForm->isSubmitted() && $canevasEditProductForm->isValid()) {
            $this->canevasService->updateBouton($canevasProduct, $canevasIndex, $request);
        }

        return $this->render('super_admin/canevas/edit.html.twig', [
            'canevas' => $canevasIndex,
            'canevas_disease_form' => $canevasDiseaseForm->createView(),
            'canevas_step_form' => $canevasStepForm->createView(),
            'canevas_add_product_form' => $canevasAddProductForm->createView(),
            'canevas_edit_product_form' => $canevasEditProductForm->createView()
        ]);
    }

    /**
     * @Route("/delete-btn", name="delete_btn", methods={"DELETE"})
     */
    public function deleteBouton(Request $request): Response
    {
        $this->canevasService->deleteButton($request->request->get('btnId'));
        return new JsonResponse();
    }


    /**
     * @Route("/new_data_ajax", name="select_product_ajax")
     */
    public function selectProductAjax(Request $request, ProductsRepository $pr): JsonResponse
    {
        //Get information from ajax call
        $term  = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        //Query of like call
        $products = $pr->createQueryBuilder('p')
            ->andWhere('p.isActive = true')
            ->andWhere('p.private = false')
            ->andWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $term . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        // Return Array of key = id && text = value
        $array = [];
        foreach($products as $product) {
            $array[] = array(
                'id' => $product->getId(),
                'text' => $product->getName()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse($array, 200);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(CanevasIndex $canevasIndex): Response
    {
        $this->canevasService->delete($canevasIndex);
        return $this->redirectBack('admin_canevas_index');
    }

    /**
     * @Route("/reactivate/{id}", name="reactivate", methods={"REACTIVATE"})
     */
    public function reactivate(CanevasIndex $canevasIndex): Response
    {
        $this->canevasService->reactivate($canevasIndex);
        return $this->redirectBack('admin_canevas_index');
    }
}

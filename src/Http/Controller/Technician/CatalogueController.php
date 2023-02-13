<?php

namespace App\Http\Controller\Technician;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Catalogue\CatalogueService;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Form\CatalogueType;
use App\Domain\Exploitation\Entity\Exploitation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/technician/catalogue", name="catalogue_")
 */
class CatalogueController extends AbstractController
{
    public function __construct(
        private readonly CatalogueService $catalogueService
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
                return $this->redirectToRoute('recommendation_product_list', ['id' => $recommendation->getId()]);
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
}

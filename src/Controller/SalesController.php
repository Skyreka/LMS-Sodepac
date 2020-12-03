<?php

namespace App\Controller;

use App\Entity\Sales;
use App\Form\SalesType;
use App\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * Class SalesController
 * @package App\Controller
 * @Route("/sales")
 */
class SalesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SalesController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    /**
     * @param SalesRepository $sr
     * @return Response
     * @Route("/", name="sales_index", methods={"GET"})
     */
    public function index( SalesRepository $sr ): Response
    {
        $sales = $sr->findBy( ['isActive' => 1] );

        return $this->render('sales/index.html.twig', [
            'sales' => $sales
        ]);
    }

    /**
     * @param SalesRepository $sr
     * @return Response
     * @Route("/manager/index", name="sales_manager_index", methods={"GET"})
     * @IsGranted("ROLE_SALES")
     */
    public function managerIndex( SalesRepository $sr ): Response
    {
        $sales = $sr->findAll();

        return $this->render('sales/manager/index.html.twig', [
            'sales' => $sales
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/manager/new", name="sales_manager_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_SALES")
     */
    public function new( Request $request ): Response
    {
        $sales = new Sales();
        $form = $this->createForm( SalesType::class, $sales);
        $form->handleRequest( $request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $sales->setAddedDate( new \DateTime() );
            $sales->setUpdateDate( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->persist( $sales );
            $em->flush();

            $this->addFlash('success', 'Nouveau cours de vente ajouté avec succès');
            return $this->redirectToRoute( 'sales_manager_index' );
        }

        return $this->render('sales/manager/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Sales $sales
     * @param Request $request
     * @return Response
     * @Route("/manager/edit/{id}", name="sales_manager_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @IsGranted("ROLE_SALES")
     */
    public function edit( Sales $sales, Request $request ): Response
    {
        $form = $this->createForm( SalesType::class, $sales);
        $form->handleRequest( $request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $sales->setUpdateDate( new \DateTime() );

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Edition du cours de vente effectuée avec succès');
            return $this->redirectToRoute( 'sales_manager_index' );
        }

        return $this->render('sales/manager/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/delete/{id}", name="sales_manager_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Sales $sales
     * @param Request $request
     * @IsGranted("ROLE_SALES")
     * @return RedirectResponse
     */
    public function delete( Sales $sales, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $sales->getId(), $request->get('_token' ))) {
            $this->em->remove( $sales );
            $this->em->flush();
            $this->addFlash('success', 'Enregistrement supprimé avec succès');
        }
        return $this->redirectToRoute('sales_manager_index');
    }
}

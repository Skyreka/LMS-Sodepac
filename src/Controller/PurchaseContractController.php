<?php

namespace App\Controller;

use App\Entity\PurchaseContract;
use App\Entity\PurchaseContractCulture;
use App\Form\PurchaseContractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseContractController
 * @package App\Controller
 */
class PurchaseContractController extends AbstractController
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
     * @return Response
     * @Route("/management/purchase-contract/", name="management_purchase_contract_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render( 'management/purchase-contract/index.html.twig' );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/management/purchase-contract/new", name="purchase_contract_new", methods={"GET", "POST"})
     */
    public function new( Request $request ): Response
    {
        $purchaseContract = new PurchaseContract();
        $form = $this->createForm( PurchaseContractType::class, $purchaseContract);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $purchaseContract->setCreator( $this->getUser() );

            $this->em->persist( $purchaseContract );

            foreach ( PurchaseContract::CULTURES as $culture ) {
                $purchaseContractCulture = new PurchaseContractCulture();
                $purchaseContractCulture->setCulture( $culture );
                $purchaseContractCulture->setPurchaseContract( $purchaseContract );
                $this->em->persist( $purchaseContractCulture );
            }

            $this->em->flush();

            $this->addFlash('success', 'Nouveau contract d\'achat crée avec succès');
            return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
        }

        return $this->render( 'management/purchase-contract/new.html.twig', [
            'form' => $form->createView()
        ] );
    }

    /**
     * @param PurchaseContract $purchaseContract
     * @return Response
     * @Route("/management/purchase-contract/show/{id}", name="management_purchase_contract_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show( PurchaseContract $purchaseContract ): Response
    {

        return $this->render( 'management/purchase-contract/show.html.twig', [
            'purchaseContract' => $purchaseContract,
            'purchaseContractCulture' => $purchaseContract->getCultures()
        ] );
    }
}

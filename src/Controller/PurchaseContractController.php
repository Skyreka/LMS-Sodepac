<?php

namespace App\Controller;

use App\Entity\PurchaseContract;
use App\Entity\PurchaseContractCulture;
use App\Form\PurchaseContractType;
use App\Repository\PurchaseContractCultureRepository;
use App\Repository\PurchaseContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param PurchaseContractRepository $pcr
     * @return Response
     * @Route("/management/purchase-contract/", name="management_purchase_contract_index", methods={"GET"})
     */
    public function index( PurchaseContractRepository $pcr ): Response
    {
        $purchaseContracts = $pcr->findBy( ['creator' => $this->getUser() ]);

        return $this->render( 'management/purchase-contract/index.html.twig', [
            'purchaseContracts' => $purchaseContracts
        ] );
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
            $purchaseContract->setStatus( 0 );

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


    /**
     * Edit Dose with editable Ajax Table
     * @Route("management/purchase-contract/culture/edit", name="purchase_contract_culture_edit")
     * @param Request $request
     * @param PurchaseContractCultureRepository $pccr
     * @return JsonResponse
     */
    public function editProduct(Request $request, PurchaseContractCultureRepository $pccr ): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $purchaseContractProduct = $pccr->find($request->get('id'));

            if ($request->get('culture')) {
                $purchaseContractProduct->setCulture( $request->get('culture' ) );
            }

            if ($request->get('volume')) {
                $purchaseContractProduct->setVolume( (float) $request->get('volume' ) );
            }

            if ($request->get('price')) {
                $purchaseContractProduct->setPrice( (float) $request->get('price' ) );
            }

            if ($request->get('transport')) {
                $purchaseContractProduct->setTransport( $request->get('transport' ) );
            }

            if ($request->get('depot')) {
                $purchaseContractProduct->setDepot( $request->get('depot' ) );
            }

            if ($request->get('recovery')) {
                $purchaseContractProduct->setRecovery( $request->get('recovery' ) );
            }

            if ($request->get('divers')) {
                $purchaseContractProduct->setDivers( $request->get('divers' ) );
            }

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
     * @Route("management/purchase-contract/delete/{id}", name="purchase_contract_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     * @param PurchaseContract $purchaseContract
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(PurchaseContract $purchaseContract, Request $request ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('deletePurchaseContract' . $purchaseContract->getId(), $request->get('_token'))) {
            $this->em->remove( $purchaseContract );
            $this->em->flush();

            $this->addFlash('success', 'Contrat d\'achat supprimé avec succès');
        }
        return $this->redirectToRoute('login_success' );
    }

    /**
     * @param PurchaseContract $purchaseContract
     * @return RedirectResponse
     * @Route("/management/purchase-contract/addline/{id}", name="management_purchase_contract_addLine", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function addLine( PurchaseContract $purchaseContract ): RedirectResponse
    {
        $purchaseContractCulture = new PurchaseContractCulture();
        $purchaseContractCulture->setPurchaseContract( $purchaseContract );
        $purchaseContractCulture->setCulture( 'Nouvelle ligne' );

        $this->em->persist( $purchaseContractCulture );
        $this->em->flush();

        $this->addFlash('success', 'Nouvelle ligne ajoutée avec succès');
        return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
    }

    /**
     * @Route("management/purchase-contract/valid/{id}", name="management_purchase_contract_valid", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param PurchaseContract $purchaseContract
     * @return RedirectResponse
     */
    public function valid( PurchaseContract $purchaseContract ): RedirectResponse
    {
        $purchaseContract->setStatus( 1 );
        $this->em->flush();
        $this->addFlash('success', 'Contract envoyé avec succès');
        return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
    }
}

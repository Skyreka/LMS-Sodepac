<?php

namespace App\Http\Controller;

use App\Domain\Purchase\Entity\PurchaseContract;
use App\Domain\Purchase\Entity\PurchaseContractCulture;
use App\Domain\Purchase\Form\PurchaseContractType;
use App\Domain\Purchase\Repository\PurchaseContractCultureRepository;
use App\Domain\Purchase\Repository\PurchaseContractRepository;
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
 * @Route("/management/purchase-contract")
 */
class PurchaseContractController extends AbstractController
{
    public function __construct(public readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/", name="management_purchase_contract_index", methods={"GET"})
     */
    public function index(PurchaseContractRepository $pcr): Response
    {
        if($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $purchaseContracts = $pcr->findBy(['creator' => $this->getUser()], ['added_date' => 'DESC']);
        } else {
            $purchaseContracts = $pcr->findBy(['status' => 1], ['added_date' => 'DESC']);
        }
        
        return $this->render('management/purchase-contract/index.html.twig', [
            'purchaseContracts' => $purchaseContracts
        ]);
    }
    
    /**
     * @Route("/new", name="purchase_contract_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $purchaseContract = new PurchaseContract();
        $form             = $this->createForm(PurchaseContractType::class, $purchaseContract);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $purchaseContract->setCreator($this->getUser());
            $purchaseContract->setStatus(0);
            
            $this->em->persist($purchaseContract);
            
            foreach(PurchaseContract::CULTURES as $culture) {
                $purchaseContractCulture = new PurchaseContractCulture();
                $purchaseContractCulture->setCulture($culture);
                $purchaseContractCulture->setPurchaseContract($purchaseContract);
                $this->em->persist($purchaseContractCulture);
            }
            
            $this->em->flush();
            
            $this->addFlash('success', 'Nouveau contrat d\'achat créé avec succès');
            return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
        }
        
        return $this->render('management/purchase-contract/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/show/{id}", name="management_purchase_contract_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(PurchaseContract $purchaseContract): Response
    {
        // Security
        if($purchaseContract->getCreator() != $this->getUser()
            && $this->getUser()->getStatus() != 'ROLE_SALES'
            && $this->getUser()->getStatus() != 'ROLE_ADMIN') {
            throw $this->createNotFoundException();
        }
        return $this->render('management/purchase-contract/show.html.twig', [
            'purchaseContract' => $purchaseContract,
            'purchaseContractCulture' => $purchaseContract->getCultures()
        ]);
    }
    
    
    /**
     * Edit Dose with editable Ajax Table
     * @Route("/edit", name="purchase_contract_culture_edit")
     */
    public function editProduct(Request $request, PurchaseContractCultureRepository $pccr): JsonResponse
    {
        if($request->isXmlHttpRequest()) {
            $purchaseContractProduct = $pccr->find($request->get('id'));
            
            if($request->get('culture')) {
                $purchaseContractProduct->setCulture($request->get('culture'));
            }
            
            if($request->get('volume')) {
                $purchaseContractProduct->setVolume((float)$request->get('volume'));
            }
            
            if($request->get('price')) {
                $purchaseContractProduct->setPrice((float)$request->get('price'));
            }
            
            if($request->get('transport')) {
                $purchaseContractProduct->setTransport($request->get('transport'));
            }
            
            if($request->get('depot')) {
                $purchaseContractProduct->setDepot($request->get('depot'));
            }
            
            if($request->get('recovery')) {
                $purchaseContractProduct->setRecovery($request->get('recovery'));
            }
            
            if($request->get('divers')) {
                $purchaseContractProduct->setDivers($request->get('divers'));
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
     * @Route("/delete/{id}", name="purchase_contract_delete", methods={"DELETE"}, requirements={"id":"\d+"})
     */
    public function delete(PurchaseContract $purchaseContract, Request $request): RedirectResponse
    {
        if($this->isCsrfTokenValid('deletePurchaseContract' . $purchaseContract->getId(), $request->get('_token'))) {
            $this->em->remove($purchaseContract);
            $this->em->flush();
            
            $this->addFlash('success', 'Contrat d\'achat supprimé avec succès');
        }
        return $this->redirectToRoute('login_success');
    }
    
    /**
     * @Route("/add-line/{id}", name="management_purchase_contract_addLine", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function addLine(PurchaseContract $purchaseContract): RedirectResponse
    {
        $purchaseContractCulture = new PurchaseContractCulture();
        $purchaseContractCulture->setPurchaseContract($purchaseContract);
        $purchaseContractCulture->setCulture('Nouvelle ligne');
        
        $this->em->persist($purchaseContractCulture);
        $this->em->flush();
        
        $this->addFlash('success', 'Nouvelle ligne ajoutée avec succès');
        return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
    }
    
    /**
     * @Route("/valid/{id}", name="management_purchase_contract_valid", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function valid(PurchaseContract $purchaseContract): RedirectResponse
    {
        $purchaseContract->setStatus(1);
        $this->em->flush();
        $this->addFlash('success', 'Contrat envoyé avec succès');
        return $this->redirectToRoute('management_purchase_contract_show', ['id' => $purchaseContract->getId()]);
    }
}

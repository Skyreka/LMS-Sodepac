<?php

namespace App\Http\Controller;

use App\Domain\Sales\Entity\Sales;
use App\Domain\Sales\Entity\SalesInformation;
use App\Domain\Sales\Form\SalesInformationType;
use App\Domain\Sales\Form\SalesType;
use App\Domain\Sales\Repository\SalesInformationRepository;
use App\Domain\Sales\Repository\SalesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SalesController
 * @package App\Controller
 * @Route("/sales", name="sales_")
 */
class SalesController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @param SalesRepository $sr
     * @param SalesInformationRepository $sir
     * @return Response
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SalesRepository $sr, SalesInformationRepository $sir): Response
    {
        $sales            = $sr->findBy(['isActive' => 1], ['culture' => 'ASC']);
        $salesInformation = $sir->find(1);
        
        return $this->render('sales/index.html.twig', [
            'sales' => $sales,
            'sales_information' => $salesInformation
        ]);
    }
    
    /**
     * @Route("/manager/index", name="manager_index", methods={"GET"})
     * @IsGranted("ROLE_SALES")
     */
    public function managerIndex(SalesRepository $sr): Response
    {
        $sales = $sr->findAllSales();
        
        return $this->render('sales/manager/index.html.twig', [
            'sales' => $sales
        ]);
    }
    
    /**
     * @Route("/manager/new", name="manager_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_SALES")
     */
    public function new(Request $request): Response
    {
        $sales = new Sales();
        $form  = $this->createForm(SalesType::class, $sales);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $sales->setAddedDate(new \DateTime());
            $sales->setUpdateDate(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($sales);
            $em->flush();
            
            $this->addFlash('success', 'Nouveau cours de vente ajouté avec succès');
            return $this->redirectToRoute('sales_manager_index');
        }
        
        return $this->render('sales/manager/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/manager/edit/{id}", name="manager_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @IsGranted("ROLE_SALES")
     */
    public function edit(Sales $sales, Request $request): Response
    {
        $form = $this->createForm(SalesType::class, $sales);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $sales->setUpdateDate(new \DateTime());
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $this->addFlash('success', 'Edition du cours de vente effectuée avec succès');
            return $this->redirectToRoute('sales_manager_index');
        }
        
        return $this->render('sales/manager/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/manager/delete/{id}", name="manager_delete", methods="DELETE", requirements={"id":"\d+"})
     * @IsGranted("ROLE_SALES")
     */
    public function delete(Sales $sales, Request $request): RedirectResponse
    {
        if($this->isCsrfTokenValid('delete' . $sales->getId(), $request->get('_token'))) {
            $this->em->remove($sales);
            $this->em->flush();
            $this->addFlash('success', 'Enregistrement supprimé avec succès');
        }
        return $this->redirectToRoute('sales_manager_index');
    }
    
    /**
     * @Route("/manager/information", name="manager_information", methods={"GET", "POST"})
     * @IsGranted("ROLE_SALES")
     */
    public function information(Request $request, SalesInformationRepository $sir): Response
    {
        // If no have already on db create after just edit
        if($sir->find(1)) {
            $salesInformation = $sir->find(1);
        } else {
            $salesInformation = new SalesInformation();
        }
        
        $form = $this->createForm(SalesInformationType::class, $salesInformation);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($salesInformation);
            $this->em->flush();
            
            $this->addFlash('success', 'Message mis à jour avec succès');
            return $this->redirectToRoute('sales_manager_index');
        }
        
        return $this->render('sales/manager/information.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

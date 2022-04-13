<?php

namespace App\Http\Controller\Interventions;

use App\Domain\Culture\Entity\Cultures;
use App\Domain\Intervention\Form\AnalyseInterventionType;
use App\Domain\Intervention\Form\DefaultInterventionType;
use App\Domain\Intervention\Form\EditInterventionQuantityType;
use App\Domain\Intervention\Form\EpandageInterventionType;
use App\Domain\Intervention\Form\FertilisantInterventionType;
use App\Domain\Intervention\Form\InterventionAddProductType;
use App\Domain\Intervention\Form\IrrigationInterventionType;
use App\Domain\Intervention\Form\PhytoInterventionType;
use App\Domain\Intervention\Form\RecolteType;
use App\Domain\Intervention\Form\SemisInterventionType;
use App\Domain\Intervention\Repository\InterventionsProductsRepository;
use App\Domain\Intervention\Repository\InterventionsRepository;
use App\Domain\Stock\Repository\StocksRepository;
use App\Domain\Intervention\Entity\Analyse;
use App\Domain\Intervention\Entity\Binage;
use App\Domain\Intervention\Entity\Epandage;
use App\Domain\Intervention\Entity\Fertilisant;
use App\Domain\Intervention\Entity\Interventions;
use App\Domain\Intervention\Entity\InterventionsProducts;
use App\Domain\Intervention\Entity\Irrigation;
use App\Domain\Intervention\Entity\Labour;
use App\Domain\Intervention\Entity\Phyto;
use App\Domain\Intervention\Entity\Recolte;
use App\Domain\Intervention\Entity\Semis;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InterventionsController
 * @package App\Controller
 * @Route("/exploitation/ilot/culture/intervention")
 */
class InterventionsController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/recolte/{id}", name="intervention_recolte", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function recolte(Cultures $culture, Request $request): Response
    {
        $name         = "Récolte";
        $intervention = new Recolte();
        $form         = $this->createForm(RecolteType::class, $intervention);
        $form->handleRequest($request);
        
        //-- Culture update status
        if($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $culture->setStatus(1);
                    $this->em->merge($intervention);
                    $this->em->merge($culture);
                    $this->em->flush();
                }
                
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                //-- Simple intervention for one culture
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $culture->setStatus(1);
                $this->em->persist($intervention);
                $this->em->flush();
                
                // If is culture multiple create new and archive old
                if($culture->getPermanent() == 1) {
                    $newCulture = clone $culture;
                    $newCulture->setStatus(0);
                    $this->em->persist($newCulture);
                    $this->em->flush();
                    $this->addFlash('warning', 'Duplication de votre culture permanente');
                }
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('ilots_show', ['id' => $culture->getIlot()->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/binage/{id}", name="intervention_binage", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function binage(Cultures $culture, Request $request): Response
    {
        $name         = "Binage";
        $intervention = new Binage();
        $form         = $this->createForm(DefaultInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/labour/{id}", name="intervention_labour", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function labour(Cultures $culture, Request $request): Response
    {
        $name         = "Labour";
        $intervention = new Labour();
        $form         = $this->createForm(DefaultInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/epandage/{id}", name="intervention_epandage", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function epandage(Cultures $culture, Request $request): Response
    {
        $name         = "Epandage";
        $intervention = new Epandage();
        $form         = $this->createForm(EpandageInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/semis/{id}", name="intervention_semis", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function semis(Cultures $culture, Request $request): Response
    {
        $name         = "Semis";
        $intervention = new Semis();
        $form         = $this->createForm(SemisInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/irrigation/{id}", name="intervention_irrigation", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function irrigation(Cultures $culture, Request $request): Response
    {
        $name         = "Irrigation";
        $intervention = new Irrigation();
        $form         = $this->createForm(IrrigationInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/analyse/{id}", name="intervention_analyse", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function analyse(Cultures $culture, Request $request): Response
    {
        $name         = "Analyse";
        $intervention = new Analyse();
        $form         = $this->createForm(AnalyseInterventionType::class, $intervention);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    $intervention->setIsMultiple(1);
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                return $this->redirectToRoute('login_success');
            } else {
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/phyto-{name}/{id}", name="intervention_phyto", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function phyto(Cultures $culture, $name, Request $request, StocksRepository $sr, InterventionsRepository $ir): Response
    {
        $intervention = new Phyto();
        
        // For total Size
        $isMultiple = false;
        
        //-- If multiple action is actived
        if($this->container->get('session')->get('listCulture')) {
            $isMultiple = true;
            // get total size of selected multiple culture
            $cultureTotalSize = 0;
            foreach($this->container->get('session')->get('listCulture') as $culture) {
                $cultureTotalSize = $cultureTotalSize + $culture->getSize();
            }
            
            // return form for multiple intervention and size of total
            $form = $this->createForm(PhytoInterventionType::class, $intervention, [
                'user' => $this->getUser(),
                'culture' => $culture,
                'totalSizeMultipleIntervention' => $cultureTotalSize
            ]);
        } else {
            //-- Return normal view
            $form = $this->createForm(PhytoInterventionType::class, $intervention, [
                'user' => $this->getUser(),
                'culture' => $culture
            ]);
        }
        $form->handleRequest($request);
        
        //-- Warning Message of already intervention last 48 hours
        $lastPhyto = $ir->findPhyto($culture);
        $last2Days = new \DateTime();
        $last2Days->modify('-2 days');
        $warningMessage = false;
        if($lastPhyto && $lastPhyto->getInterventionAt() >= $last2Days) {
            $warningMessage = true;
        }
        
        //-- Form Submit
        if($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data  = $form->all();
            $stock = $data['productInStock']->getData();
            //-- Update Stock
            $stock           = $sr->find(['id' => $stock]);
            $quantityUsed    = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity($quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity($quantityUsedInStock + $quantityUsed);
            //-- Create Array List Intervention if past have mulitple product intervention
            $listIntervention = [];
            //-- Multiple Intervention
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    //-- Setters
                    $intervention->setIsMultiple(1);
                    $intervention->setSizeMultiple($cultureTotalSize);
                    $intervention->setDose($data['doses']->getData()->getDose());
                    $intervention->setDoseHectare($data['doseHectare']->getData());
                    $intervention->setProduct($stock->getProduct());
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    $intervention->setDoseUnit($data['doses']->getNormData()->getUnit());
                    //-- Flush on db
                    $lastIntervention = $this->em->merge($intervention);
                    $this->em->flush();
                    //-- Push to array all interventions id generated on DB
                    array_push($listIntervention, $lastIntervention->getId());
                }
                //-- Clear listCulture
                //$this->container->get('session')->remove('listCulture');
                
                //-- Multiple product on mutiple intervention
                if($data['addProduct']->getData()) {
                    // Create listIntervention SESSION with array pre generated
                    $this->container->get('session')->set('listIntervention', $listIntervention);
                    // Go to page for add product
                    return $this->redirectToRoute('interventions_phyto_product', [
                        'name' => $name,
                        'id' => $lastIntervention->getId()
                    ]);
                }
                
                //--Flash Message
                $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
                $this->addFlash('warning', 'Stock de ' . $stock->getProduct()->getName() . ' mis à jour. Nouvelle valeur en stock ' . $stock->getQuantity() . ' ' . $stock->getUnit(true));
                
                //-- Go to home
                return $this->redirectToRoute('login_success');
            } else {
                // Normal Action no multiple
                //-- Setters
                $intervention->setDose($data['doses']->getData()->getDose());
                $intervention->setDoseHectare($data['doseHectare']->getData());
                $intervention->setProduct($stock->getProduct());
                $intervention->setCulture($culture);
                $intervention->setType($name);
                $intervention->setDoseUnit($data['doses']->getNormData()->getUnit());
                //-- Flush on db
                $this->em->persist($intervention);
                $this->em->flush();
            }
            //-- Flash Message
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            $this->addFlash('warning', 'Stock de ' . $stock->getProduct()->getName() . ' mis à jour. Nouvelle valeur en stock ' . $stock->getQuantity() . ' ' . $stock->getUnit(true));
            //-- Redirect to add new product if checkbox is checked
            if($data['addProduct']->getData()) {
                return $this->redirectToRoute('interventions_phyto_product', [
                    'name' => $name,
                    'id' => $intervention->getId()
                ]);
            }
            //-- Or redirect to culture
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/phyto.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView(),
            'warningMessage' => $warningMessage,
            'cultureSize' => $isMultiple ? $cultureTotalSize : $culture->getSize()
        ]);
    }
    
    /**
     * @Route("/phyto-{name}/{id}/product", name="interventions_phyto_product", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function phytoAddProduct(Interventions $intervention, Request $request, StocksRepository $sr, InterventionsProductsRepository $ipr, InterventionsRepository $ir, $name): Response
    {
        $isMultiple = false;
        
        $interventionProduct = new InterventionsProducts();
        $form                = $this->createForm(InterventionAddProductType::class, $interventionProduct, [
            'user' => $this->getUser(),
            'culture' => $intervention->getCulture()
        ]);
        $form->handleRequest($request);
        
        //-- If multiple action is actived
        if($this->container->get('session')->get('listCulture')) {
            $isMultiple = true;
            
            // get total size of selected multiple culture
            $cultureTotalSize = 0;
            foreach($this->container->get('session')->get('listCulture') as $culture) {
                $cultureTotalSize = $cultureTotalSize + $culture->getSize();
            }
        }
        
        
        if($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data  = $form->all();
            $stock = $data['productInStock']->getData();
            $dose  = $data['doses']->getData();
            //-- Update Stock
            $stock           = $sr->find(['id' => $stock]);
            $quantityUsed    = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity($quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity($quantityUsedInStock + $quantityUsed);
            //-- If Multiple Intervention is activated
            if($this->container->get('session')->get('listIntervention')) {
                //-- Get all id of listintervention sesssion
                $listIntervention = $this->container->get('session')->get('listIntervention');
                // For of all intervention
                foreach($listIntervention as $idIntervention) {
                    // Get intervention object with id DB
                    $interventionToPut = $ir->find($idIntervention);
                    // Setter of multiple interventoion_product
                    $interventionProduct->setDose($dose->getDose());
                    $interventionProduct->setQuantity($form->getData()->getQuantity());
                    $interventionProduct->setProduct($stock->getProduct());
                    $interventionProduct->setIntervention($interventionToPut);
                    $interventionProduct->setDoseHectare($data['doseHectare']->getData());
                    // Save on DB
                    $this->em->merge($interventionProduct);
                    $this->em->flush();
                }
                
                //-- Multiple product on mutiple intervention
                if($data['addProduct']->getData()) {
                    // If user want to loop
                    return $this->redirectToRoute('interventions_phyto_product', [
                        'name' => $name,
                        'id' => $intervention->getId()
                    ]);
                } else {
                    //-- Clear listIntervention only if user not want a loop
                    $this->container->get('session')->remove('listIntervention');
                    // Flash message
                    $this->addFlash('success', 'Nouveau produit ajouté avec succès');
                    // Go to home
                    return $this->redirectToRoute('login_success');
                }
                
            } else {
                // Normal Actions
                // Setters
                $interventionProduct->setDose($dose->getDose());
                $interventionProduct->setQuantity($form->getData()->getQuantity());
                $interventionProduct->setProduct($stock->getProduct());
                $interventionProduct->setIntervention($intervention);
                $interventionProduct->setDoseHectare($data['doseHectare']->getData());
                $this->em->persist($interventionProduct);
                $this->em->flush();
                // Flash Messages
                $this->addFlash('success', 'Nouveau produit ajouté avec succès');
                $this->addFlash('warning', 'Stock de ' . $stock->getProduct()->getName() . ' mis à jour. Nouvelle valeur en stock ' . $stock->getQuantity() . ' ' . $stock->getUnit(true));
            }
            
            //-- Redirect to add new product if checkbox is checked
            if($data['addProduct']->getData()) {
                return $this->redirectToRoute('interventions_phyto_product', [
                    'name' => $name,
                    'id' => $intervention->getId()
                ]);
            }
            
            // Go to cultures show
            return $this->redirectToRoute('cultures_show', [
                'id' => $intervention->getCulture()->getId()
            ]);
        }
        
        return $this->render('interventions/addProduct.html.twig', [
            'form' => $form->createView(),
            'culture' => $intervention->getCulture(),
            'intervention' => $intervention,
            'interventionProducts' => $ipr->findBy(['intervention' => $intervention]),
            'cultureSize' => $isMultiple ? $cultureTotalSize : $intervention->getCulture()->getSize()
        ]);
    }
    
    /**
     * @Route("/fertilisant/{id}", name="intervention_fertilisant", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function fertilisant(Cultures $culture, Request $request, StocksRepository $sr, InterventionsRepository $ir): Response
    {
        $isMultiple   = false;
        $name         = 'Fertilisant';
        $intervention = new Fertilisant();
        $form         = $this->createForm(FertilisantInterventionType::class, $intervention, [
            'user' => $this->getUser(),
            'culture' => $culture
        ]);
        $form->handleRequest($request);
        
        //-- Warning Message of already intervention last 48 hours
        $lastPhyto = $ir->findPhyto($culture);
        $last2Days = new \DateTime();
        $last2Days->modify('-2 days');
        $warningMessage = false;
        if($lastPhyto && $lastPhyto->getInterventionAt() >= $last2Days) {
            $warningMessage = true;
        }
        
        if($this->container->get('session')->get('listCulture')) {
            $isMultiple = true;
            
            // get total size of selected multiple culture
            $cultureTotalSize = 0;
            foreach($this->container->get('session')->get('listCulture') as $culture) {
                $cultureTotalSize = $cultureTotalSize + $culture->getSize();
            }
        }
        
        if($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data  = $form->all();
            $stock = $data['productInStock']->getData();
            //-- Update Stock
            $stock           = $sr->find(['id' => $stock]);
            $quantityUsed    = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity($quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity($quantityUsedInStock + $quantityUsed);
            //-- Multiple Intervention
            if($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach($listCulture as $culture) {
                    //-- Setters
                    $intervention->setIsMultiple(1);
                    $intervention->setProduct($stock->getProduct());
                    $intervention->setCulture($culture);
                    $intervention->setType($name);
                    //-- Flush on db
                    $this->em->merge($intervention);
                    $this->em->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                return $this->redirectToRoute('login_success');
            } else {
                //-- Setters
                $intervention->setProduct($stock->getProduct());
                $intervention->setCulture($culture);
                $intervention->setType($name);
                //-- Flush on db
                $this->em->persist($intervention);
                $this->em->flush();
            }
            $this->addFlash('success', 'Intervention de ' . $name . ' créée avec succès');
            $this->addFlash('warning', 'Stock de ' . $stock->getProduct()->getName() . ' mis à jour. Nouvelle valeur en stock ' . $stock->getQuantity() . ' ' . $stock->getUnit(true));
            return $this->redirectToRoute('cultures_show', ['id' => $culture->getId()]);
        }
        
        return $this->render('interventions/fertilisant.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView(),
            'warningMessage' => $warningMessage,
            'cultureSize' => $isMultiple ? $cultureTotalSize : $culture->getSize()
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="intervention_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Interventions $intervention, Request $request, StocksRepository $sr)
    {
        
        $classMetadata    = $this->em->getClassMetadata(get_class($intervention));
        $discriminatorMap = $classMetadata->discriminatorValue;
        
        // Freeze var before form on phyto only
        if($discriminatorMap == 'phyto') {
            $quantityOnIntervention = $intervention->getQuantity();
        }
        
        switch($intervention->getType()) {
            case 'Récolte':
                $form = $this->createForm(RecolteType::class, $intervention, ['syntheseView' => true]);
                break;
            
            case 'Epandage':
                $form = $this->createForm(EpandageInterventionType::class, $intervention);
                break;
            
            case 'Désherbant':
                $form = $this->createForm(EditInterventionQuantityType::class, $intervention);
                break;
            
            case 'Insecticide':
                $form = $this->createForm(EditInterventionQuantityType::class, $intervention);
                break;
            
            case 'Nutrition':
                $form = $this->createForm(EditInterventionQuantityType::class, $intervention);
                break;
            
            case 'Fertilisant':
                $form = $this->createForm(EditInterventionQuantityType::class, $intervention);
                break;
            
            case 'Fongicide':
                $form = $this->createForm(EditInterventionQuantityType::class, $intervention);
                break;
            
            default:
                $form = $this->createForm(DefaultInterventionType::class, $intervention);
        }
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //-- Update Stock
            if($discriminatorMap == 'phyto') {
                $stock                    = $sr->findOneBy(['product' => $intervention->getProduct(), 'exploitation' => $this->getUser()->getExploitation()]);
                $quantityNew              = $form->getData()->getQuantity();
                $quantityOnStock          = $stock->getQuantity();
                $diffQuantityIntervention = $quantityNew - $quantityOnIntervention;
                $stock->setQuantity($quantityOnStock - $diffQuantityIntervention);
                $quantityUsedInStock = $stock->getUsedQuantity();
                $stock->setUsedQuantity($quantityUsedInStock + $diffQuantityIntervention);
            }
            
            $this->em->flush();
            $this->addFlash('success', 'Intervention modifiée avec succès');
            return $this->redirectToRoute('cultures.synthese', ['id' => $intervention->getCulture()->getId()]);
        }
        
        return $this->render('interventions/edit.html.twig', [
            'culture' => $intervention->getCulture(),
            'intervention' => $intervention->getType(),
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="intervention_delete", methods="DELETE")
     */
    public function delete(Interventions $interventions, Request $request)
    {
        if($this->isCsrfTokenValid('deleteIntervention' . $interventions->getId(), $request->get('_token'))) {
            $this->em->remove($interventions);
            $this->em->flush();
            $this->addFlash('success', 'Intervention supprimé avec succès');
        }
        return $this->redirect($request->headers->get('referer'));
    }
}

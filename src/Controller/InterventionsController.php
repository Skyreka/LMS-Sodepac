<?php
namespace App\Controller;

use App\Entity\Binage;
use App\Entity\Cultures;
use App\Entity\Epandage;
use App\Entity\Fertilisant;
use App\Entity\Interventions;
use App\Entity\InterventionsProducts;
use App\Entity\Labour;
use App\Entity\Phyto;
use App\Entity\Products;
use App\Entity\Recolte;
use App\Entity\Semis;
use App\Form\DefaultInterventionType;
use App\Form\EditInterventionQuantityType;
use App\Form\EpandageInterventionType;
use App\Form\FertilisantInterventionType;
use App\Form\InterventionAddProductType;
use App\Form\PhytoInterventionType;
use App\Form\RecolteType;
use App\Form\SemisInterventionType;
use App\Repository\CulturesRepository;
use App\Repository\InterventionsProductsRepository;
use App\Repository\InterventionsRepository;
use App\Repository\StocksRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterventionsController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $om;
    /**
     * @var CulturesRepository
     */
    private $cr;

    /**
     * InterventionsController constructor.
     * @param EntityManagerInterface $om
     */
    public function __construct(EntityManagerInterface $om)
    {

        $this->om = $om;
    }

    /**
     * @Route("interventions/recolte/{id}", name="interventions.recolte.new")
     * @param Cultures $culture
     * @param Request $request
     * @return Response
     */
    public function recolte(Cultures $culture, Request $request): Response
    {
        $name = "Récolte";
        $intervention = new Recolte();
        $form = $this->createForm( RecolteType::class, $intervention);
        $form->handleRequest( $request );

        //-- Culture update status
        if ($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    $culture->setStatus( 1 );
                    $this->om->merge( $intervention );
                    $this->om->merge( $culture );
                    $this->om->flush();
                }

                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                return $this->redirectToRoute('login.success');
            } else {
                //-- Simple intervention for one culture
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                $culture->setStatus( 1 );
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'ilots.show', ['id' => $culture->getIlot()->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("interventions/binage/{id}", name="interventions.binage.new")
     * @param Cultures $culture
     * @param Request $request
     * @return Response
     */
    public function binage(Cultures $culture, Request $request): Response
    {
        $name = "Binage";
        $intervention = new Binage();
        $form = $this->createForm( DefaultInterventionType::class, $intervention);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    $this->om->merge( $intervention );
                    $this->om->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                return $this->redirectToRoute('login.success');
            } else {
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("interventions/labour/{id}", name="interventions.labour.new")
     * @param Cultures $culture
     * @param Request $request
     * @return Response
     */
    public function labour(Cultures $culture, Request $request): Response
    {
        $name = "Labour";
        $intervention = new Labour();
        $form = $this->createForm( DefaultInterventionType::class, $intervention);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //-- If is multiple intervention
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    $this->om->merge( $intervention );
                    $this->om->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                return $this->redirectToRoute('login.success');
            } else {
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("interventions/epandage/{id}", name="interventions.epandage.new")
     * @param Cultures $culture
     * @param Request $request
     * @return Response
     */
    public function epandage(Cultures $culture, Request $request): Response
    {
        $name = "Epandage";
        $intervention = new Epandage();
        $form = $this->createForm( EpandageInterventionType::class, $intervention);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    $this->om->merge( $intervention );
                    $this->om->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                return $this->redirectToRoute('login.success');
            } else {
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("interventions/semis/{id}", name="interventions.semis.new")
     * @param Cultures $culture
     * @param Request $request
     * @return Response
     */
    public function semis(Cultures $culture, Request $request): Response
    {
        $name = "Semis";
        $intervention = new Semis();
        $form = $this->createForm( SemisInterventionType::class, $intervention);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    $this->om->merge( $intervention );
                    $this->om->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                return $this->redirectToRoute('login.success');
            } else {
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("interventions/phyto/{id}/{name}", name="interventions.phyto.new")
     * @param Cultures $culture
     * @param $name
     * @param Request $request
     * @param StocksRepository $sr
     * @param InterventionsRepository $ir
     * @return Response
     * @throws \Exception
     */
    public function phyto(Cultures $culture, $name, Request $request, StocksRepository $sr, InterventionsRepository $ir): Response
    {
        $intervention = new Phyto();
        //-- If multiple action is actived
        if ($this->container->get('session')->get('listCulture')) {
            // get total size of selected multiple culture
            $cultureTotalSize = 0;
            foreach ( $this->container->get('session')->get('listCulture') as $culture ) {
                $cultureTotalSize = $cultureTotalSize + $culture->getSize();
            }
            // return form for multiple intervention and size of total
            $form = $this->createForm( PhytoInterventionType::class, $intervention, [
                'user' => $this->getUser(),
                'culture' => $culture,
                'totalSizeMultipleIntervention' => $cultureTotalSize
            ]);
        } else {
            //-- Return normal view
            $form = $this->createForm( PhytoInterventionType::class, $intervention, [
                'user' => $this->getUser(),
                'culture' => $culture
            ]);
        }
        $form->handleRequest( $request );

        //-- Warning Message of already intervention last 48 hours
        $lastPhyto = $ir->findPhyto( $culture );
        $last2Days = new \DateTime();
        $last2Days->modify( '-2 days');
        $warningMessage = false;
        if ( $lastPhyto && $lastPhyto->getInterventionAt() >= $last2Days ) {
            $warningMessage = true;
        }

        //-- Form Submit
        if ($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data = $form->all();
            $stock = $data['productInStock']->getData();
            //-- Update Stock
            $stock = $sr->find( ['id' => $stock] );
            $quantityUsed = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity( $quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity( $quantityUsedInStock + $quantityUsed );
            //-- Create Array List Intervention if past have mulitple product intervention
            $listIntervention = [];
            //-- Multiple Intervention
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    //-- Setters
                    $intervention->setDose( $data['doses']->getData()->getDose() );
                    $intervention->setProduct( $stock->getProduct() );
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    //-- Flush on db
                    $lastIntervention = $this->om->merge( $intervention );
                    $this->om->flush();
                    //-- Push to array all interventions id generated on DB
                    array_push( $listIntervention, $lastIntervention->getId() );
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');

                //-- Multiple product on mutiple intervention
                if ( $data['addProduct']->getData() ) {
                    // Create listIntervention SESSION with array pre generated
                    $this->container->get('session')->set('listIntervention', $listIntervention);
                    // Go to page for add product
                    return $this->redirectToRoute('interventions.phyto.product', ['id' => $lastIntervention->getId()]);
                }

                //--Flash Message
                $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
                $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));

                //-- Go to home
                return $this->redirectToRoute('login.success');
            } else {
                // Normal Action no multiple
                //-- Setters
                $intervention->setDose( $data['doses']->getData()->getDose() );
                $intervention->setProduct( $stock->getProduct() );
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                //-- Flush on db
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            //-- Flash Message
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));
            //-- Redirect to add new product if checkbox is checked
            if ( $data['addProduct']->getData() ) {
                return $this->redirectToRoute('interventions.phyto.product', ['id' => $intervention->getId()]);
            }
            //-- Or redirect to culture
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/phyto.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView(),
            'warningMessage' => $warningMessage
        ]);
    }

    /**
     * Add product to an intervention
     * @Route("interventions/phyto/{id}", name="interventions.phyto.product")
     * @param Interventions $intervention
     * @param Request $request
     * @param StocksRepository $sr
     * @param InterventionsProductsRepository $ipr
     * @param InterventionsRepository $ir
     * @return Response
     */
    public function addProduct(Interventions $intervention, Request $request, StocksRepository $sr, InterventionsProductsRepository $ipr, InterventionsRepository $ir): Response
    {
        $interventionProduct = new InterventionsProducts();
        $form = $this->createForm( InterventionAddProductType::class, $interventionProduct, [
            'user' => $this->getUser(),
            'culture' => $intervention->getCulture()
        ]);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data = $form->all();
            $stock = $data['productInStock']->getData();
            $dose = $data['doses']->getData();
            //-- Update Stock
            $stock = $sr->find( ['id' => $stock] );
            $quantityUsed = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity( $quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity( $quantityUsedInStock + $quantityUsed );
            //-- If Multiple Intervention is activated
            if ($this->container->get('session')->get('listIntervention')) {
                //-- Get all id of listintervention sesssion
                $listIntervention = $this->container->get('session')->get('listIntervention');
                // For of all intervention
                foreach ($listIntervention as $idIntervention) {
                    // Get intervention object with id DB
                    $interventionToPut = $ir->find( $idIntervention );
                    // Setter of multiple interventoion_product
                    $interventionProduct->setDose( $dose->getDose() );
                    $interventionProduct->setQuantity( $form->getData()->getQuantity() );
                    $interventionProduct->setProduct( $stock->getProduct() );
                    $interventionProduct->setIntervention( $interventionToPut );
                    // Save on DB
                    $this->om->merge( $interventionProduct );
                    $this->om->flush();
                }

                //-- Multiple product on mutiple intervention
                if ( $data['addProduct']->getData() ) {
                    // If user want to loop
                    return $this->redirectToRoute('interventions.phyto.product', ['id' => $intervention->getId()]);
                } else {
                    //-- Clear listIntervention only if user not want a loop
                    $this->container->get('session')->remove('listIntervention');
                    // Flash message
                    $this->addFlash('success', 'Nouveau produit ajouté avec succès');
                    // Go to home
                    return $this->redirectToRoute('login.success');
                }

            } else {
                // Normal Actions
                // Setters
                $interventionProduct->setDose( $dose->getDose() );
                $interventionProduct->setQuantity( $form->getData()->getQuantity() );
                $interventionProduct->setProduct( $stock->getProduct() );
                $interventionProduct->setIntervention( $intervention );
                $this->om->persist( $interventionProduct );
                $this->om->flush();
                // Flash Messages
                $this->addFlash('success', 'Nouveau produit ajouté avec succès');
                $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));
            }

            //-- Redirect to add new product if checkbox is checked
            if ( $data['addProduct']->getData() ) {
                return $this->redirectToRoute('interventions.phyto.product', [
                    'id' => $intervention->getId()
                ]);
            }

            // Go to cultures show
            return $this->redirectToRoute('cultures.show', [
                'id' => $intervention->getCulture()->getId()
            ]);
        }

        return $this->render('interventions/addProduct.html.twig', [
            'form' => $form->createView(),
            'culture' => $intervention->getCulture(),
            'intervention' => $intervention,
            'interventionProducts' => $ipr->findBy( ['intervention' => $intervention] )
        ]);
    }

    /**
     * @Route("interventions/phyto-delete/{interventionsProducts}", name="interventions.phyto.product.delete", methods="DELETE")
     * @param InterventionsProducts $interventionsProducts
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteNewProduct(InterventionsProducts $interventionsProducts, Request $request)
    {
        $intervention = $interventionsProducts->getIntervention();
        if ($this->isCsrfTokenValid('delete' . $interventionsProducts->getId(), $request->get('_token'))) {
            $this->om->remove( $interventionsProducts );
            $this->om->flush();
        }

        return $this->redirectToRoute('interventions.phyto.product', ['id' => $intervention->getId()]);
    }

    /**
     * @Route("interventions/fertilisant/{id}", name="interventions.fertilisant.new")
     * @param Cultures $culture
     * @param Request $request
     * @param StocksRepository $sr
     * @param InterventionsRepository $ir
     * @return Response
     * @throws \Exception
     */
    public function fertilisant(Cultures $culture, Request $request, StocksRepository $sr, InterventionsRepository $ir): Response
    {
        $name = 'Fertilisant';
        $intervention = new Fertilisant();
        $form = $this->createForm( FertilisantInterventionType::class, $intervention, [
            'user' => $this->getUser(),
            'culture' => $culture
        ]);
        $form->handleRequest( $request );

        //-- Warning Message of already intervention last 48 hours
        $lastPhyto = $ir->findPhyto( $culture );
        $last2Days = new \DateTime();
        $last2Days->modify( '-2 days');
        $warningMessage = false;
        if ( $lastPhyto && $lastPhyto->getInterventionAt() >= $last2Days ) {
            $warningMessage = true;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Get data
            $data = $form->all();
            $stock = $data['productInStock']->getData();
            //-- Update Stock
            $stock = $sr->find( ['id' => $stock] );
            $quantityUsed = $form->getData()->getQuantity();
            $quantityOnStock = $stock->getQuantity();
            $stock->setQuantity( $quantityOnStock - $quantityUsed);
            $quantityUsedInStock = $stock->getUsedQuantity();
            $stock->setUsedQuantity( $quantityUsedInStock + $quantityUsed );
            //-- Multiple Intervention
            if ($this->container->get('session')->get('listCulture')) {
                //-- Foreach of all culture selected
                $listCulture = $this->container->get('session')->get('listCulture');
                foreach ($listCulture as $culture) {
                    //-- Setters
                    $intervention->setProduct( $stock->getProduct() );
                    $intervention->setCulture( $culture );
                    $intervention->setType( $name );
                    //-- Flush on db
                    $this->om->merge( $intervention );
                    $this->om->flush();
                }
                //-- Clear listCulture
                $this->container->get('session')->remove('listCulture');
                return $this->redirectToRoute('login.success');
            } else {
                //-- Setters
                $intervention->setProduct( $stock->getProduct() );
                $intervention->setCulture( $culture );
                $intervention->setType( $name );
                //-- Flush on db
                $this->om->persist( $intervention );
                $this->om->flush();
            }
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/fertilisant.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView(),
            'warningMessage' => $warningMessage
        ]);
    }

    /**
     * @Route("interventions/edit/{id}", name="interventions.edit")
     * @param Interventions $intervention
     * @param Request $request
     * @return Response
     */
    public function edit( Interventions $intervention, Request $request ) {
        switch ($intervention->getType()) {
            case 'Récolte':
                $form = $this->createForm( RecolteType::class, $intervention, ['syntheseView' => true] );
                break;

            case 'Epandage':
                $form = $this->createForm( EpandageInterventionType::class, $intervention );
                break;

            case 'Désherbant':
                $form = $this->createForm( EditInterventionQuantityType::class, $intervention );
                break;

            case 'Insecticide':
                $form = $this->createForm( EditInterventionQuantityType::class, $intervention );
                break;

            case 'Nutrition':
                $form = $this->createForm( EditInterventionQuantityType::class, $intervention );
                break;

            case 'Fertilisant':
                $form = $this->createForm( EditInterventionQuantityType::class, $intervention );
                break;

            case 'Fongicide':
                $form = $this->createForm( EditInterventionQuantityType::class, $intervention );
                break;

            default:
                $form = $this->createForm( DefaultInterventionType::class, $intervention );
        }


        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid()) {
            $this->om->flush();
            $this->addFlash('success', 'Intervention modifiée avec succès');
            return $this->redirectToRoute( 'cultures.synthese', ['id' => $intervention->getCulture()->getId()] );
        }

        return $this->render('interventions/edit.html.twig', [
            'culture' => $intervention->getCulture(),
            'intervention' => $intervention->getType(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/intervention-product/delete/{id}", name="interventions.product.delete", methods="DELETE")
     * @param InterventionsProducts $interventionsProducts
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(InterventionsProducts $interventionsProducts, Request $request)
    {
        if ($this->isCsrfTokenValid( 'deleteProduct', $request->get('_token') )) {
            $this->om->remove( $interventionsProducts );
            $this->om->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
            return $this->redirectToRoute( 'interventions.phyto.product', ['id' => $interventionsProducts->getIntervention()->getId()] );
        }
        return $this->redirectToRoute('home');
    }
}
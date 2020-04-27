<?php
namespace App\Controller;

use App\Entity\Binage;
use App\Entity\Cultures;
use App\Entity\Epandage;
use App\Entity\Fertilisant;
use App\Entity\Interventions;
use App\Entity\Labour;
use App\Entity\Phyto;
use App\Entity\Recolte;
use App\Entity\Semis;
use App\Entity\UsedProducts;
use App\Form\DefaultInterventionType;
use App\Form\EpandageInterventionType;
use App\Form\FertilisantInterventionType;
use App\Form\PhytoAddAdjuvantType;
use App\Form\PhytoInterventionType;
use App\Form\RecolteType;
use App\Form\SemisInterventionType;
use App\Repository\CulturesRepository;
use App\Repository\InterventionsRepository;
use App\Repository\StocksRepository;
use App\Repository\UsedProductsRepository;
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
        $form = $this->createForm( PhytoInterventionType::class, $intervention, [
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
            //-- Setters
            $intervention->setProduct( $stock->getProduct() );
            $intervention->setCulture( $culture );
            $intervention->setType( $name );
            //-- Flush on db
            $this->om->persist( $intervention );
            $this->om->flush();
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));
            //-- Redirect to add Adjuvant if checkbox is checked
            if ( $data['addAdjuvant']->getData() ) {
                return $this->redirectToRoute('interventions.phyto.adjuvant', ['id' => $intervention->getId()]);
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
     * Update Adjuvant on exist intervention
     * @Route("interventions/phyto/{id}", name="interventions.phyto.adjuvant")
     * @param Interventions $interventions
     * @param Request $request
     * @param StocksRepository $sr
     * @return Response
     */
    public function phytoAddAdjuvant(Interventions $interventions, Request $request, StocksRepository $sr): Response
    {
        $form = $this->createForm( PhytoAddAdjuvantType::class, $interventions, [
            'user' => $this->getUser(),
            'culture' => $interventions->getCulture()
        ]);
        $form->handleRequest( $request );

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
            //-- Update Adjuvant
            $interventions->setAdjuvant( $stock->getProduct() );
            //-- Flush on db
            $this->om->flush();
            $this->addFlash('success', 'Adjuvant ajouté avec succès');
            $this->addFlash('warning', 'Stock de '. $stock->getProduct()->getName() .' mis à jour. Nouvelle valeur en stock '. $stock->getQuantity() .' '.$stock->getUnit( true ));
            return $this->redirectToRoute( 'cultures.show', ['id' => $interventions->getCulture()->getId()] );
        }

        return $this->render('interventions/phytoAddAdjuvant.html.twig', [
            'form' => $form->createView(),
            'culture' => $interventions->getCulture()
        ]);
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
            //-- Setters
            $intervention->setProduct( $stock->getProduct() );
            $intervention->setCulture( $culture );
            $intervention->setType( $name );
            //-- Flush on db
            $this->om->persist( $intervention );
            $this->om->flush();
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
        $form = $this->createForm( DefaultInterventionType::class, $intervention );
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
}
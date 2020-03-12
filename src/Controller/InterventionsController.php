<?php
namespace App\Controller;

use App\Entity\Binage;
use App\Entity\Cultures;
use App\Entity\Epandage;
use App\Entity\Interventions;
use App\Entity\Labour;
use App\Entity\Recolte;
use App\Entity\Semis;
use App\Form\DefaultInterventionType;
use App\Form\EpandageInterventionType;
use App\Form\IntEpandageType;
use App\Form\SemisInterventionType;
use Doctrine\Common\Persistence\ObjectManager;
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
     * InterventionsController constructor.
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
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
        $form = $this->createForm( DefaultInterventionType::class, $intervention);
        $form->handleRequest( $request );

        $intervention->setCulture( $culture );
        $intervention->setType( $name );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $intervention );
            $this->om->flush();
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

        $intervention->setCulture( $culture );
        $intervention->setType( $name );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $intervention );
            $this->om->flush();
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

        $intervention->setCulture( $culture );
        $intervention->setType( $name );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $intervention );
            $this->om->flush();
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

        $intervention->setCulture( $culture );
        $intervention->setType( $name );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $intervention );
            $this->om->flush();
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

        $intervention->setCulture( $culture );
        $intervention->setType( $name );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $intervention );
            $this->om->flush();
            $this->addFlash('success', 'Intervention de '. $name .' crée avec succès');
            return $this->redirectToRoute( 'cultures.show', ['id' => $culture->getId()] );
        }

        return $this->render('interventions/default.html.twig', [
            'culture' => $culture,
            'intervention' => $name,
            'form' => $form->createView()
        ]);
    }
}
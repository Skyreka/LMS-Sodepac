<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Entity\IndexCultures;
use App\Entity\Interventions;
use App\Form\CulturesNewType;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\IndexCulturesRepository;
use App\Repository\InterventionsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CulturesController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(EntityManagerInterface $om)
    {
        $this->om = $om;
    }

    /**
     * @Route("/cultures/new/{id}", name="cultures.new")
     * @param Ilots $ilot
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     */
    public function new( Ilots $ilot, Request $request, CulturesRepository $cr): Response
    {
        //-- Get Size
        $size = $cr->countAvailableSizeCulture( $ilot );
        if ($size === 0) {
            $this->addFlash('danger', 'Vous ne pouvez pas créer de culture, plus d\'espace disponible dans cette ilot');
            return $this->redirectToRoute('ilots.show', ['id' => $ilot->getId()]);
        }
        //-- Form
        $culture = new Cultures();
        $form = $this->createForm( CulturesNewType::class, $culture, ['max_size' => $size]);
        $form->handleRequest( $request );
        $culture->setIlot( $ilot );
        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $culture );
            $this->om->flush();
            $this->addFlash('success', 'Culture crée avec succès');
            return $this->redirectToRoute('ilots.show', ['id' => $ilot->getId()]);
        }
        return $this->render('cultures/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("cultures/show/{id}", name="cultures.show")
     * @param Cultures $culture
     * @param InterventionsRepository $ir
     * @return Response
     */
    public function show(Cultures $culture, InterventionsRepository $ir): Response
    {
        return $this->render('cultures/show.html.twig', [
            'culture' => $culture,
            'ir' => $ir
        ]);
    }

    /**
     * @Route("cultures", name="cultures.index")
     * @param IndexCulturesRepository $icr
     * @return Response
     */
    public function index(IndexCulturesRepository $icr): Response
    {
        $cultures = $icr->findCulturesByExploitation( $this->getUser()->getExploitation() );
        dump( $cultures );
        return $this->render('cultures/index.html.twig', [
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("cultures/{id}/ilots", name="cultures.show.ilots")
     * @param IndexCultures $indexCulture
     * @param IlotsRepository $ir
     * @return Response
     */
    public function showIlotsByCultures(IndexCultures $indexCulture, IlotsRepository $ir)
    {
        $ilots = $ir->findByIndexCulture( $indexCulture->getId(), $this->getUser()->getExploitation() );
        dump( $ilots );
        return $this->render('cultures/showIlots.html.twig', [
            'indexCulture' => $indexCulture,
            'ilots' => $ilots
        ]);
    }

    /**
     * @Route("cultures/delete/{id}", name="cultures.delete", methods="DELETE")
     * @param Cultures $culture
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Cultures $culture, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $culture->getId(), $request->get('_token'))) {
            $this->om->remove( $culture );
            $this->om->flush();
            $this->addFlash('success','Culture supprimé avec succès');
        }
        return $this->redirectToRoute('ilots.show', ['id' => $culture->getIlot()->getId()]);
    }

    /**
     * @Route("cultures/synthese/{id}", name="cultures.synthese")
     * @param Cultures $culture
     * @param InterventionsRepository $interventions
     * @return Response
     */
    public function synthese(Cultures $culture, InterventionsRepository $interventions): Response
    {
        return $this->render('cultures/synthese.html.twig', [
            'culture' => $culture,
            'interventions' => $interventions
        ]);
    }
}
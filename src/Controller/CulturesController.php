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

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @Route("/cultures/new/{id}", name="cultures.new")
     * @param Ilots $ilot
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new( Ilots $ilot, Request $request, CulturesRepository $cr): Response
    {
        //-- Get Size
        $availableSize = $cr->countAvailableSizeCulture( $ilot );

        //-- Form
        $culture = new Cultures();
        $form = $this->createForm( CulturesNewType::class, $culture, ['max_size' => $availableSize]);
        $form->handleRequest( $request );
        $culture->setIlot( $ilot );
        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist( $culture );
            $this->om->flush();
            $this->addFlash('success', 'Culture crée avec succès');
            $this->redirectToRoute('ilots.show', ['id' => $ilot->getId()]);
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
     * @param CulturesRepository $cr
     * @return Response
     */
    public function index(CulturesRepository $cr): Response
    {
        $cultures = $cr->findCulturesByExploitation( $this->getUser()->getExploitation() );
        dump($cultures);
        return $this->render('cultures/index.html.twig', [
            'cultures' => $cultures
        ]);
    }
}
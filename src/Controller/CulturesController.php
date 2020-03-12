<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Entity\Interventions;
use App\Form\CulturesNewType;
use App\Repository\IlotsRepository;
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
     * @return Response
     */
    public function new( Ilots $ilot, Request $request ): Response
    {
        $culture = new Cultures();
        $form = $this->createForm( CulturesNewType::class, $culture);
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
}
<?php
namespace App\Controller;

use App\Entity\Ilots;
use App\Form\IlotsType;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IlotsController extends AbstractController
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("ilots/new", name="ilots.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ilot = new Ilots();
        $form = $this->createForm(IlotsType::class, $ilot);
        $form->handleRequest( $request );

        $ilot->setExploitation( $this->getUser()->getExploitation()  );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($ilot);
            $this->em->flush();

            $this->addFlash('success', 'Ilot crée avec succès');
            return $this->redirectToRoute('home');
        }

        return $this->render('ilots/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("ilots/show/{id}", name="ilots.show")
     * @param Ilots $ilot
     * @param CulturesRepository $culturesRepository
     * @return Response
     */
    public function show(Ilots $ilot, CulturesRepository $culturesRepository): Response
    {
        $cultures = $culturesRepository->findBy( ['ilot' => $ilot] );
        return $this->render('ilots/show.html.twig', [
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("/ilot/delete/{id}", name="ilots.delete", methods="DELETE")
     * @param Ilots $ilot
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Ilots $ilot, Request $request)
    {
        if ($this->isCsrfTokenValid( 'deleteIlot', $request->get('_token') )) {
            $this->em->remove( $ilot );
            $this->em->flush();
            $this->addFlash('success', 'Ilot supprimé avec succès');
            return $this->redirectToRoute( 'home' );
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("ilots/edit/{id}", name="ilots.edit")
     * @param Ilots $ilot
     * @return Response
     */
    public function edit(Ilots $ilot, Request $request): Response
    {
        $form = $this->createForm( IlotsType::class, $ilot);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Ilot édité avec succès');
            return $this->redirectToRoute('ilots.show', [ 'id' => $ilot->getId() ]);
        }

        return $this->render('ilots/edit.html.twig', [
            'ilot' => $ilot,
            'form' => $form->createView()
        ]);
    }
}
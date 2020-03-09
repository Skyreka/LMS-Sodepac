<?php
namespace App\Controller;

use App\Entity\Ilots;
use App\Form\IlotsType;
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
     * @return Response
     */
    public function show(Ilots $ilot): Response
    {
        return $this->render('ilots/show.html.twig', [
            'ilot' => $ilot
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
}
<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Entity\Ilots;
use App\Form\IlotsType;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\IrrigationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("ilots/new", name="ilots.new")
     * @param Request $request
     * @param IlotsRepository $ir
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new(Request $request, IlotsRepository $ir): Response
    {
        // Get Size
        $size = $ir->countAvailableSizeIlot( $this->getUser()->getExploitation() );

        if ($size > 0) {


            //-- Create form
            $ilot = new Ilots();
            $form = $this->createForm(IlotsType::class, $ilot, ['max_size' => $size]);
            $form->handleRequest( $request );
            $ilot->setExploitation( $this->getUser()->getExploitation()  );
            if ($form->isSubmitted() && $form->isValid()) {
                //-- If pack demo
                if ( $this->getUser()->getPack() === 'PACK_DEMO' && $ilot->getSize() > 10 ) {
                    $this->addFlash('danger', "Vous avez un pack Démo, vous ne pouvez pas créer un ilot de plus 10ha");
                    return $this->redirectToRoute('login.success');
                }
                //-- Check if size available
                if ( $size < $ilot->getSize() ) {
                    $this->addFlash('danger', "Vous n'avez pas assez d'espace disponible");
                    return $this->redirectToRoute('login.success');
                }
                $this->em->persist($ilot);
                $this->em->flush();
                $this->addFlash('success', 'Ilot crée avec succès');
                return $this->redirectToRoute('ilots.show', ['id' => $ilot->getId()]);
            }

            return $this->render('ilots/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            // Return error no size
            $this->addFlash('danger', "Vous ne pouvez pas créer d'ilot, plus d'espace disponible");
            return $this->redirectToRoute('login.success');
        }
    }

    /**
     * @Route("ilots/show/{id}", name="ilots.show")
     * @param Ilots $ilot
     * @param CulturesRepository $culturesRepository
     * @return Response
     */
    public function show(Ilots $ilot, CulturesRepository $culturesRepository): Response
    {
        $cultures = $culturesRepository->findByIlot( $ilot );

        return $this->render('ilots/show.html.twig', [
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("/ilot/delete/{id}", name="ilots.delete", methods="DELETE")
     * @param Ilots $ilot
     * @param Request $request
     * @param IrrigationRepository $ir
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Ilots $ilot, Request $request, IrrigationRepository $ir)
    {
        if ($this->isCsrfTokenValid( 'deleteIlot', $request->get('_token') )) {
            $irrigations = $ir->findByIlot($ilot);
            foreach ($irrigations as $irrigation) {
                $this->em->remove($irrigation);
            }
            $culture = new Cultures();
            $ilot->removeCulture( $culture );
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

    /**
     * @Route("ilots", name="ilots.index")
     * @param IlotsRepository $ir
     * @return Response
     */
    public function index( IlotsRepository $ir): Response
    {
        return $this->render('ilots/index.html.twig', [
            'ilots' => $ir->findBy( ['exploitation' => $this->getUser()->getExploitation() ], ['name' => 'ASC'] )
        ]);
    }
}
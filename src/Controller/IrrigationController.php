<?php
namespace App\Controller;

use App\Entity\Irrigation;
use App\Form\IrrigationType;
use App\Repository\IrrigationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class IrrigationController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("exploitation/irrigation", name="exploitation.irrigation.index")
     * @param IrrigationRepository $irrigationRepository
     * @return Response
     */

    public function index(IrrigationRepository $irrigationRepository): Response
    {
        $irrigations = $irrigationRepository->findBy( ['exploitation' => $this->getUser() ] );
        return $this->render('exploitation/irrigation/index.html.twig', [
            'irrigations' => $irrigations
        ]);
    }

    /**
     * @Route("exploitation/irrigation/new", name="exploitation.irrigation.new")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */

    public function new(Request $request): Response
    {
        $irrigation  = new Irrigation();
        $form = $this->createForm(IrrigationType::class, $irrigation, ['exp' => $this->getUser()]);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $irrigation->setInterventionAt( new \DateTime());
            $irrigation->setExploitation( $this->getUser()->getExploitation() );
            $this->em->persist($irrigation);
            $this->em->flush();

            $this->addFlash('success', 'Nouvelle irrigation crée avec succès');
            return $this->redirectToRoute('exploitation.irrigation.index');
        }

        return $this->render('exploitation/irrigation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
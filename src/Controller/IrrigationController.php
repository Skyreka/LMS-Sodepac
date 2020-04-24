<?php
namespace App\Controller;

use App\Entity\Irrigation;
use App\Form\IrrigationType;
use App\Repository\IrrigationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(EntityManagerInterface $em)
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
        $arrosages = $irrigationRepository->findBy( ['exploitation' => $this->getUser()->getExploitation(), 'type' => 'Arrosage' ], ['intervention_at' => 'DESC'], 3 );
        $pluvios = $irrigationRepository->findBy( ['exploitation' => $this->getUser()->getExploitation(), 'type' => 'Pluviometrie' ], ['intervention_at' => 'DESC'], 3 );
        return $this->render('exploitation/irrigation/index.html.twig', [
            'arrosages' => $arrosages,
            'pluvios' => $pluvios
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


    /**
     * @Route("exploitation/pluviometrie/synthese", name="exploitation.pluviometrie.synthese")
     * @param IrrigationRepository $ir
     * @return Response
     */

    public function pluviometrie(IrrigationRepository $ir): Response
    {
        $year = date('Y');
        $irrigations = $ir->findByExploitationYearAndType($this->getUser()->getExploitation(), $year, 'Pluviometrie');
        $total = 0;
        foreach ($irrigations as $irrigation) {
            $total = $total + $irrigation->getQuantity();
        }
        return $this->render('exploitation/irrigation/synthese/pluviometrie.html.twig',[
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }

    /**
     * @Route("exploitation/arrosage/synthese", name="exploitation.arrosage.synthese")
     * @param IrrigationRepository $ir
     * @return Response
     */

    public function arrosage(IrrigationRepository $ir): Response
    {
        $year = date('Y');
        $irrigations = $ir->findByExploitationYearAndType($this->getUser()->getExploitation(), $year, 'Arrosage');
        $total = 0;
        foreach ($irrigations as $irrigation) {
            $total = $total + $irrigation->getQuantity();
        }
        return $this->render('exploitation/irrigation/synthese/arrosage.html.twig',[
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }

    /**
     * @Route("/exploitation/irrigation/{year}/{type}", name="user.exploitation.irrigation.data")
     * @param $year
     * @param $type
     * @param IrrigationRepository $ir
     * @return Response
     */
    public function userList($year, $type, IrrigationRepository $ir): Response
    {
        $irrigations = $ir->findByExploitationYearAndType($this->getUser()->getExploitation(), $year, $type);
        $total = 0;
        foreach ($irrigations as $irrigation) {
            $total = $total + $irrigation->getQuantity();
        }
        return $this->render('exploitation/irrigation/synthese/data.html.twig', [
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }
}
<?php
namespace App\Controller;

use App\Entity\Irrigation;
use App\Form\IrrigationType;
use App\Repository\IlotsRepository;
use App\Repository\IrrigationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class IrrigationController
 * @package App\Controller
 * @Route("/exploitation")
 */
class IrrigationController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/irrigation", name="exploitation_irrigation_index", methods={"GET"})
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
     * @Route("/irrigation/delete/{id}", name="irrigation_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Irrigation $irrigation
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Irrigation $irrigation, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $irrigation->getId(), $request->get('_token' ))) {
            $this->em->remove($irrigation);
            $this->em->flush();
            $this->addFlash('success', 'Irrigation supprimée avec succès');
        }
        return $this->redirectToRoute('exploitation_irrigation_index');
    }

    /**
     * @Route("/irrigation/new/{type}", name="exploitation_irrigation_new", methods={"GET", "POST"})
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
            $irrigation->setType( $request->attributes->get('type'));
            $this->em->persist($irrigation);
            $this->em->flush();

            $this->addFlash('success', 'Nouvelle irrigation crée avec succès');
            return $this->redirectToRoute('exploitation_irrigation_index');
        }

        return $this->render('exploitation/irrigation/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/pluviometrie/synthese", name="exploitation_pluviometrie_synthese", methods={"GET"})
     * @param IrrigationRepository $ir
     * @return Response
     */

    public function pluviometrie(IrrigationRepository $ir): Response
    {
        $year = date('Y');
        $irrigations = $ir->findByExploitationYearAndType($this->getUser()->getExploitation(), $year, 'Pluviometrie');
        $total = $ir->countTotalOfYear($this->getUser()->getExploitation(), $year, 'Pluviometrie');
        return $this->render('exploitation/irrigation/synthese/pluviometrie.html.twig',[
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }

    /**
     * @Route("/arrosage/synthese", name="exploitation_arrosage_synthese", methods={"GET"})
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
     * @Route("/irrigation/{year}/{type}", name="user_exploitation_irrigation_data", methods={"GET", "POST"}, requirements={"year":"\d+"})
     * @param $year
     * @param $type
     * @param IrrigationRepository $ir
     * @return Response
     */
    public function userList($year, $type, IrrigationRepository $ir): Response
    {
        $irrigations = $ir->findByExploitationYearAndType($this->getUser()->getExploitation(), $year, $type);
        $total = $ir->countTotalOfYear($this->getUser()->getExploitation(), $year, $type);
        return $this->render('exploitation/irrigation/synthese/data.html.twig', [
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }

    /**
     * @Route("/arrosage/", name="exploitation_synthese_arrosage_ilots_index", methods={"GET"})
     * @param IlotsRepository $ir
     * @return Response
     */
    public function arrosageSyntheIlotsIndex(IlotsRepository $ir)
    {
        return $this->render('exploitation/irrigation/synthese/ilots/arrosage/index.html.twig',[
            'ilots' => $ir->findBy( ['exploitation' => $this->getUser()->getExploitation() ], ['name' => 'ASC'] )
        ]);
    }

    /**
     * @Route("/arrosage/{ilot}", name="exploitation_synthese_arrosage_ilots_show", methods={"GET", "POST"}, requirements={"ilot":"\d+"})
     * @param $ilot
     * @param IrrigationRepository $ir
     * @param IlotsRepository $ilr
     * @return Response
     */
    public function arrosageSyntheIlotsShow($ilot, IrrigationRepository $ir, IlotsRepository $ilr)
    {
        $year = date('Y');
        return $this->render('exploitation/irrigation/synthese/ilots/arrosage/show.html.twig',[
            'irrigations' => $ir->findByIlotYearAndType($ilot, $year, 'Arrosage'),
            'total' => $ir->countTotalOfYearOnIlot($ilot, $year, 'Arrosage'),
            'ilot' => $ilr->find($ilot)
        ]);
    }

    /**
     * @Route("/pluviometrie/", name="exploitation_synthese_pluviometrie_ilots_index", methods={"GET"})
     * @param IlotsRepository $ir
     * @return Response
     */
    public function pluviometrieSyntheIlotsIndex(IlotsRepository $ir)
    {
        return $this->render('exploitation/irrigation/synthese/ilots/pluviometrie/index.html.twig',[
            'ilots' => $ir->findBy( ['exploitation' => $this->getUser()->getExploitation() ], ['name' => 'ASC'] )
        ]);
    }

    /**
     * @Route("/pluviometrie/{ilot}", name="exploitation.synthese.pluviometrie.ilots.show", methods={"GET"}, requirements={"ilot":"\d+"})
     * @param $ilot
     * @param IrrigationRepository $ir
     * @param IlotsRepository $ilr
     * @return Response
     */
    public function pluviometrieSyntheIlotsShow($ilot, IrrigationRepository $ir, IlotsRepository $ilr)
    {
        $year = date('Y');
        return $this->render('exploitation/irrigation/synthese/ilots/pluviometrie/show.html.twig',[
            'irrigations' => $ir->findByIlotYearAndType($ilot, $year, 'Pluviometrie'),
            'total' => $ir->countTotalOfYearOnIlot($ilot, $year, 'Pluviometrie'),
            'ilot' => $ilr->find($ilot)
        ]);
    }

    /**
     * @Route("/irrigation/{year}/{type}/{ilot}", name="user_exploitation_irrigation_ilot_data", methods={"GET"}, requirements={"year":"\d+", "ilot":"\d+"})
     * @param $year
     * @param $type
     * @param $ilot
     * @param IrrigationRepository $ir
     * @return Response
     */
    public function syntheseIlotData($year, $type,$ilot, IrrigationRepository $ir): Response
    {
        $irrigations = $ir->findByIlotYearAndType($ilot, $year, $type);
        $total = $ir->countTotalOfYearOnIlot($ilot, $year, $type);
        return $this->render('exploitation/irrigation/synthese/data.html.twig', [
            'irrigations' => $irrigations,
            'total' => $total
        ]);
    }
}
<?php
namespace App\Controller;

use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnalyseController
 * @package App\Controller
 * @Route("/exploitation/analyse")
 */
class AnalyseController extends AbstractController
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
     * @Route("/", name="exploitation_analyse_index", methods={"GET"})
     * @param AnalyseRepository $analyseRepository
     * @return Response
     */

    public function index(AnalyseRepository $analyseRepository): Response
    {
        $analyses = $analyseRepository->findByExploitation( $this->getUser()->getExploitation() );
        return $this->render('exploitation/analyse/index.html.twig', [
            'analyses' => $analyses
        ]);
    }

    /**
     * @Route("/new", name="exploitation_analyse_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */

    public function new(Request $request): Response
    {
        $analyse  = new Analyse();
        $form = $this->createForm(AnalyseType::class, $analyse, ['exp' => $this->getUser()]);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $analyse->setInterventionAt( new \DateTime());
            $analyse->setExploitation( $this->getUser()->getExploitation() );
            $this->em->persist($analyse);
            $this->em->flush();

            $this->addFlash('success', 'Nouvelle analyse de terre crée avec succès');
            return $this->redirectToRoute('exploitation_analyse_index');
        }

        return $this->render('exploitation/analyse/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
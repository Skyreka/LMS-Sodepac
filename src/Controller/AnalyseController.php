<?php
namespace App\Controller;

use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Repository\AnalyseRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyseController extends AbstractController
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
     * @Route("exploitation/analyse", name="exploitation.analyse.index")
     * @param AnalyseRepository $analyseRepository
     * @return Response
     */

    public function index(AnalyseRepository $analyseRepository): Response
    {
        $analyses = $analyseRepository->findByUser( $this->getUser() );
        return $this->render('exploitation/analyse/index.html.twig', [
            'analyses' => $analyses
        ]);
    }

    /**
     * @Route("exploitation/irrigation/new", name="exploitation.analyse.new")
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
            return $this->redirectToRoute('exploitation.analyse.index');
        }

        return $this->render('exploitation/analyse/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
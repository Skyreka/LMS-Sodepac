<?php
namespace App\Controller;

use App\Entity\IndexCultures;
use App\Entity\IndexEffluents;
use App\Form\IndexCulturesType;
use App\Form\IndexEffluentsType;
use App\Repository\EffluentsRepository;
use App\Repository\IndexCulturesRepository;
use App\Repository\IndexEffluentsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TreeHouse\Slugifier\Slugifier;

class IndexsController extends AbstractController
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
     * @Route("/admin/indexs", name="indexs.index")
     * @param IndexCulturesRepository $icr
     * @param IndexEffluentsRepository $ier
     * @return Response
     */
    public function index( IndexCulturesRepository $icr, IndexEffluentsRepository $ier): Response
    {
        $cultures = $icr->findAll();
        $effluents = $ier->findAll();
        return $this->render('indexs/index.html.twig', [
            'cultures' => $cultures,
            'effluents' => $effluents
        ]);
    }

    /**
     * @Route("/admin/indexs/cultures/new", name="indexs.cultures.new")
     * @param Request $request
     * @return Response
     */
    public function newCultures(Request $request): Response
    {
        $indexCultures = new IndexCultures();
        $slugify = new Slugifier();
        $form = $this->createForm( IndexCulturesType::class, $indexCultures);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $indexCultures->setSlug( $slugify->slugify( $form->getData()->getName() ) );
            $this->em->persist( $indexCultures );
            $this->em->flush();
        }

        return $this->render( 'indexs/cultures/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/indexs/effluents/new", name="indexs.effluents.new")
     * @param Request $request
     * @return Response
     */
    public function newEffluents(Request $request): Response
    {
        $effluents = new IndexEffluents();
        $slugify = new Slugifier();
        $form = $this->createForm( IndexEffluentsType::class, $effluents);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $effluents->setSlug( $slugify->slugify( $form->getData()->getName() ) );
            $this->em->persist( $effluents );
            $this->em->flush();
        }

        return $this->render( 'indexs/effluents/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
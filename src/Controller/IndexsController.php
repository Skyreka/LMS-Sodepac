<?php
namespace App\Controller;

use App\Entity\IndexCultures;
use App\Form\IndexCulturesType;
use App\Repository\IndexCulturesRepository;
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
     * @return Response
     */
    public function index( IndexCulturesRepository $icr): Response
    {
        $cultures = $icr->findAll();
        return $this->render('indexs/index.html.twig', [
            'cultures' => $cultures
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
}
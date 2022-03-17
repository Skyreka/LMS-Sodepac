<?php
namespace App\Controller\Admin;

use App\Entity\IndexCultures;
use App\Entity\IndexEffluents;
use App\Entity\IndexGrounds;
use App\Form\IndexCulturesType;
use App\Form\IndexEffluentsType;
use App\Form\IndexGroundsType;
use App\Repository\EffluentsRepository;
use App\Repository\IndexCulturesRepository;
use App\Repository\IndexEffluentsRepository;
use App\Repository\IndexGroundsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TreeHouse\Slugifier\Slugifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class IndexsController
 * @package App\Controller
 * @IsGranted("ROLE_SUPERADMIN")
 * @Route("admin/indexs")
 */
class IndexsController extends AbstractController
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
     * @Route("", name="indexs_index", methods={"GET"})
     * @param IndexCulturesRepository $icr
     * @param IndexEffluentsRepository $ier
     * @param IndexGroundsRepository $igr
     * @return Response
     */
    public function index( IndexCulturesRepository $icr, IndexEffluentsRepository $ier, IndexGroundsRepository $igr): Response
    {
        $cultures = $icr->findAllAlpha();
        $effluents = $ier->findAll();
        $grounds = $igr->findAll();
        return $this->render('indexs/index.html.twig', [
            'cultures' => $cultures,
            'effluents' => $effluents,
            'grounds' => $grounds
        ]);
    }

    /**
     * @Route("cultures/new", name="indexs_cultures_new", methods={"GET", "POST"})
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
            $this->addFlash('success', 'Culture créee avec succès');

            return $this->redirectToRoute('indexs_index');
        }

        return $this->render( 'indexs/cultures/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("grounds/new", name="indexs_grounds_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function newGrounds(Request $request): Response
    {
        $indexGrounds = new IndexGrounds();
        $slugify = new Slugifier();
        $form = $this->createForm( IndexGroundsType::class, $indexGrounds);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $indexGrounds->setSlug( $slugify->slugify( $form->getData()->getName() ) );
            $this->em->persist( $indexGrounds );
            $this->em->flush();

            $this->addFlash('success', 'Type de sol crée avec succès');

            return $this->redirectToRoute('indexs_index');
        }

        return $this->render( 'indexs/grounds/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("effluents/new", name="indexs_effluents_new", methods={"GET", "POST"})
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
            $this->addFlash('success', 'Apport d\'effluents créé avec succès');

            return $this->redirectToRoute('indexs_index');
        }

        return $this->render( 'indexs/effluents/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

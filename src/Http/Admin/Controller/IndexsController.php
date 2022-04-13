<?php

namespace App\Http\Admin\Controller;

use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Entity\IndexEffluents;
use App\Domain\Index\Entity\IndexGrounds;
use App\Domain\Index\Form\IndexCulturesType;
use App\Domain\Index\Form\IndexEffluentsType;
use App\Domain\Index\Form\IndexGroundsType;
use App\Domain\Index\Repository\IndexCulturesRepository;
use App\Domain\Index\Repository\IndexEffluentsRepository;
use App\Domain\Index\Repository\IndexGroundsRepository;
use App\Repository\EffluentsRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TreeHouse\Slugifier\Slugifier;

/**
 * @IsGranted("ROLE_SUPERADMIN")
 * @Route("indexs", name="indexs_")
 */
class IndexsController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(IndexCulturesRepository $icr, IndexEffluentsRepository $ier, IndexGroundsRepository $igr): Response
    {
        $cultures  = $icr->findAllAlpha();
        $effluents = $ier->findAll();
        $grounds   = $igr->findAll();
        return $this->render('indexs/index.html.twig', [
            'cultures' => $cultures,
            'effluents' => $effluents,
            'grounds' => $grounds
        ]);
    }
    
    /**
     * @Route("/cultures/new", name="cultures_new", methods={"GET", "POST"})
     */
    public function newCultures(Request $request): Response
    {
        $indexCultures = new IndexCultures();
        $slugify       = new Slugify();
        $form          = $this->createForm(IndexCulturesType::class, $indexCultures);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $indexCultures->setSlug($slugify->slugify($form->getData()->getName()));
            $this->em->persist($indexCultures);
            $this->em->flush();
            $this->addFlash('success', 'Culture créee avec succès');
            
            return $this->redirectToRoute('admin_indexs_index');
        }
        
        return $this->render('indexs/cultures/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/grounds/new", name="grounds_new", methods={"GET", "POST"})
     */
    public function newGrounds(Request $request): Response
    {
        $indexGrounds = new IndexGrounds();
        $slugify      = new Slugifier();
        $form         = $this->createForm(IndexGroundsType::class, $indexGrounds);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $indexGrounds->setSlug($slugify->slugify($form->getData()->getName()));
            $this->em->persist($indexGrounds);
            $this->em->flush();
            
            $this->addFlash('success', 'Type de sol crée avec succès');
            
            return $this->redirectToRoute('admin_indexs_index');
        }
        
        return $this->render('indexs/grounds/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/effluents/new", name="effluents_new", methods={"GET", "POST"})
     */
    public function newEffluents(Request $request): Response
    {
        $effluents = new IndexEffluents();
        $slugify   = new Slugifier();
        $form      = $this->createForm(IndexEffluentsType::class, $effluents);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $effluents->setSlug($slugify->slugify($form->getData()->getName()));
            $this->em->persist($effluents);
            $this->em->flush();
            $this->addFlash('success', 'Apport d\'effluents créé avec succès');
            
            return $this->redirectToRoute('admin_indexs_index');
        }
        
        return $this->render('indexs/effluents/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

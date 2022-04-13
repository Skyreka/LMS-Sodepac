<?php

namespace App\Http\Controller;

use App\Domain\Mix\Entity\Mix;
use App\Domain\Mix\Entity\MixProducts;
use App\Domain\Mix\Form\MixAddProductType;
use App\Domain\Mix\Form\MixAddType;
use App\Domain\Mix\Repository\MixProductsRepository;
use App\Domain\Mix\Repository\MixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MixController
 * @package App\Controller
 * @Route("/mix", name="mix_")
 */
class MixController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MixRepository $mr): Response
    {
        
        return $this->render('mix/index.html.twig', [
            'mixs' => $mr->findBy(['user' => $this->getUser()])
        ]);
    }
    
    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $mix  = new Mix();
        $form = $this->createForm(MixAddType::class, $mix);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $mix->setCreateAt($date);
            $mix->setUser($this->getUser());
            $this->em->persist($mix);
            $this->em->flush();
            return $this->redirectToRoute('mix_show', ['id' => $mix->getId()]);
        }
        
        return $this->render('mix/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/add-product/{id}", name="add_product", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function addProduct(Request $request, Mix $mix): Response
    {
        $mixProduct = new MixProducts();
        $form       = $this->createForm(MixAddProductType::class, $mixProduct);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $mixProduct->setMix($mix);
            $this->em->persist($mixProduct);
            $this->em->flush();
            return $this->redirectToRoute('mix_show', ['id' => $mix->getId()]);
        }
        
        return $this->render('mix/addProduct.html.twig', [
            'mix' => $mix,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/show/{id}", name="show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Mix $mix, MixProductsRepository $mpr): Response
    {
        $mixProducts = $mpr->findBy(['mix' => $mix]);
        return $this->render('mix/view.html.twig', [
            'mix' => $mix,
            'mixProducts' => $mixProducts
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods="DELETE", requirements={"id":"\d+"})
     */
    public function delete(Mix $mix, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $mix->getId(), $request->get('_token'))) {
            $mixProducts = new MixProducts();
            $mix->removeMixProducts($mixProducts);
            $this->em->remove($mix);
            $this->em->flush();
            $this->addFlash('success', 'Mélange supprimée avec succès');
        }
        return $this->redirectToRoute('mix_index');
    }
}

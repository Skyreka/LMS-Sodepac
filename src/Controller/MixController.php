<?php

namespace App\Controller;

use App\Entity\Mix;
use App\Entity\MixProducts;
use App\Form\MixAddProductType;
use App\Form\MixAddType;
use App\Repository\MixProductsRepository;
use App\Repository\MixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MixController
 * @package App\Controller
 * @Route("/mix")
 */
class MixController extends AbstractController
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
     * @param MixRepository $mr
     * @return Response
     * @Route("/", name="mix_index", methods={"GET"})
     */
    public function mixIndex( MixRepository $mr ) : Response
    {

        return $this->render('mix/index.html.twig', [
            'mixs' => $mr->findBy( ['user' => $this->getUser()])
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/new", name="mix_new", methods={"GET", "POST"})
     */
    public function mixNew( Request $request ) : Response
    {
        $mix = new Mix();
        $form = $this->createForm(MixAddType::class, $mix );
        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() ) {
            $date = new \DateTime();
            $mix->setCreateAt( $date );
            $mix->setUser( $this->getUser() );
            $this->em->persist( $mix );
            $this->em->flush();
            return $this->redirectToRoute('mix_show', ['id' => $mix->getId()]);
        }

        return $this->render('mix/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Mix $mix
     * @return Response
     * @Route("/add-product/{id}", name="mix_add_product", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function mixAddProduct( Request $request, Mix $mix ) : Response
    {
        $mixProduct = new MixProducts();
        $form = $this->createForm( MixAddProductType::class, $mixProduct);
        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() ) {
            $mixProduct->setMix( $mix );
            $this->em->persist( $mixProduct );
            $this->em->flush();
            return $this->redirectToRoute('mix_show', ['id' => $mix->getId()]);
        }

        return $this->render('mix/addProduct.html.twig', [
            'mix' => $mix,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Mix $mix
     * @param MixProductsRepository $mpr
     * @return Response
     * @Route("/show/{id}", name="mix_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function mixShow( Mix $mix, MixProductsRepository $mpr ) : Response
    {
        $mixProducts = $mpr->findBy( ['mix' => $mix ]);
        return $this->render('mix/view.html.twig', [
            'mix' => $mix,
            'mixProducts' => $mixProducts
        ]);
    }

    /**
     * @Route("/delete/{id}", name="mix_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Mix $mix
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Mix $mix, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $mix->getId(), $request->get('_token' ))) {
            $mixProducts = new MixProducts();
            $mix->removeMixProducts( $mixProducts );
            $this->em->remove($mix);
            $this->em->flush();
            $this->addFlash('success', 'Mélange supprimée avec succès');
        }
        return $this->redirectToRoute('mix_index');
    }
}
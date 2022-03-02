<?php
namespace App\Controller;

use App\Entity\Doses;
use App\Entity\Products;
use App\Entity\Stocks;
use App\Form\ProductsType;
use App\Form\StockAddProductType;
use App\Form\StockEditQuantityType;
use App\Repository\StocksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StockController
 * @package App\Controller
 * @Route("exploitation/stock")
 */
class StockController extends AbstractController
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
     * @Route("", name="exploitation_stock_index", methods={"GET"})
     * @param StocksRepository $stocksRepository
     * @return Response
     */
    public function index(StocksRepository $stocksRepository): Response
    {
        $stocks = $stocksRepository->findByExploitation( $this->getUser()->getExploitation(), true );
        return $this->render('exploitation/stock/index.html.twig', [
            'stocks' => $stocks
        ]);
    }

    /**
     * @Route("/new", name="exploitation_stock_new", methods={"GET", "POST"})
     * @param Request $request
     * @param StocksRepository $sr
     * @return Response
     */
    public function new(Request $request, StocksRepository $sr): Response
    {
        $stock = new Stocks();
        $form = $this->createForm( StockAddProductType::class, $stock);
        $form->handleRequest( $request );

        $oldStocks = $sr->findBy(array('exploitation' => $this->getUser()->getExploitation()));
        $stockProducts = [];
        foreach ($oldStocks as $oldStock) {
            $stockProducts[] = $oldStock->getProduct()->getId();
        }

        $stock->setExploitation( $this->getUser()->getExploitation() );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($stock);
            //--Setter
            if (in_array($stock->getProduct()->getId(), $stockProducts)) {
                $this->addFlash('danger', 'Vous possedez déjà ce produit dans votre stock.');
            } else {
                $this->em->flush();
                $this->addFlash('success', 'Nouveau produit ajouté avec succès');
            }

            return $this->redirectToRoute('exploitation_stock_index');
        }

        return $this->render('exploitation/stock/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/private/new", name="exploitation_stock_private_new", methods={"GET", "POST"})
     * @param Request $request
     * @param StocksRepository $sr
     * @return Response
     */
    public function newPrivate(Request $request, StocksRepository $sr): Response
    {
        $product = new Products();
        $form = $this->createForm( ProductsType::class, $product);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            //Create product
            $product->setPrivate(1);
            $product->setSlug('produit-personnel');
            $dose = new Doses();
            $dose->setApplication('Cliquez ici');
            $dose->setProduct($product);
            $this->em->persist($dose);
            $this->em->persist($product);
            //Create stock
            $stock = new Stocks();
            $stock->setProduct($product);
            $stock->setExploitation($this->getUser()->getExploitation());
            $stock->setUnit(1);
            $this->em->persist($stock);
            $this->em->flush();
            //Redirect
            $this->addFlash('success', 'Nouveau produit ajouté avec succès');
            return $this->redirectToRoute('exploitation_stock_index');
        }

        return $this->render('exploitation/stock/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="exploitation_stock_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Stocks $stock
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Stocks $stock, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $stock->getId(), $request->get('_token'))) {
            $this->em->remove($stock);
            $this->em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('exploitation_stock_index');
    }

    /**
     * @Route("/edit/{id}", name="exploitation_stock_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Stocks $stock
     * @param Request $request
     * @return Response
     */
    public function edit(Stocks $stock, Request $request): Response
    {
        $form = $this->createForm(StockEditQuantityType::class, $stock);
        $form->handleRequest( $request );

        //-- Get precedentQuantity before submit of form
        $precedentQuantity = $stock->getQuantity();

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Add new value of quantity to existing value on product in stock
            $data = $form->all();
            $newQuantity = $data['addQuantity']->getData();
            $stock->setQuantity( $precedentQuantity + $newQuantity );
            $this->em->flush();
            $this->addFlash('success','Mise à jour effectuée avec succès');
            return $this->redirectToRoute('exploitation_stock_index');
        }

        return $this->render('exploitation/stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form->createView()
        ]);
    }
}

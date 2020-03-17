<?php
namespace App\Controller;

use App\Entity\Stocks;
use App\Form\StockAddProductType;
use App\Form\StockEditQuantityType;
use App\Repository\StocksRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * StockController constructor.
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @Route("exploitation/stock", name="exploitation.stock.index")
     * @param StocksRepository $stocksRepository
     * @return Response
     */
    public function index(StocksRepository $stocksRepository): Response
    {
        $stocks = $stocksRepository->findAll();
        return $this->render('exploitation/stock/index.html.twig', [
            'stocks' => $stocks
        ]);
    }

    /**
     * @Route("exploitation/stock/add", name="exploitation.stock.add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $stock = new Stocks();
        $form = $this->createForm( StockAddProductType::class, $stock);
        $form->handleRequest( $request );

        $stock->setExploitation( $this->getUser()->getExploitation() );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->persist($stock);
            //--Setter
            $this->om->flush();
            $this->addFlash('success', 'Nouveau produit ajouté avec succès');
            return $this->redirectToRoute('exploitation.stock.index');
        }

        return $this->render('exploitation/stock/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("exploitation/stock/delete/{id}", name="exploitation.stock.delete", methods="DELETE")
     * @param Stocks $stock
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Stocks $stock, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $stock->getId(), $request->get('_token'))) {
            $this->om->remove($stock);
            $this->om->flush();
            $this->addFlash('success', 'Produit supprimé avec succès');
        }
        return $this->redirectToRoute('exploitation.stock.index');
    }

    /**
     * @Route("exploitation/stock/edit/{id}", name="exploitation.stock.update")
     * @param Stocks $stock
     * @return Response
     */
    public function update(Stocks $stock, Request $request): Response
    {
        $form = $this->createForm(StockEditQuantityType::class, $stock);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $this->om->flush();
            $this->addFlash('success','Mise à jour effectuée avec succès');
            return $this->redirectToRoute('exploitation.stock.index');
        }

        return $this->render('exploitation/stock/update.html.twig', [
            'stock' => $stock,
            'form' => $form->createView()
        ]);
    }
}
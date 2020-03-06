<?php

namespace App\Controller;

use App\Entity\Bsv;
use App\Form\BsvType;
use App\Repository\BsvRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBsvController extends AbstractController
{
    /**
     * @var BsvRepository
     */
    private $repositoryBsv;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(BsvRepository $repository, ObjectManager $em)
    {
        $this->repositoryBsv = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/bsv", name="admin.bsv.index")
     * @return Response
     */
    public function index(): Response
    {
        $bsv = $this->repositoryBsv->findAllNotSent();
        return $this->render('admin/bsv/index.html.twig', [
            'bsv' => $bsv
        ]);
    }

    /**
     * @Route("/admin/bsv/new", name="admin.bsv.new")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $bsv = new Bsv();
        $form = $this->createForm(BsvType::class, $bsv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = New \DateTime();
            $bsv->setCreationDate( $datetime );
            //* TO DO (remove setter (default value))
            $bsv->setSent( 0 );
            $this->em->persist($bsv);
            $this->em->flush();


            $this->addFlash('success', 'BSV crée avec succès');

            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/bsv/{id}", name="admin.bsv.delete", methods="DELETE")
     * @param Bsv $bsv
     * @return RedirectResponse
     */
    public function delete(Bsv $bsv, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $bsv->getId(), $request->get('_token'))) {
            $this->em->remove($bsv);
            $this->em->flush();
            $this->addFlash('success', 'BSV supprimé avec succès');
        }

        return $this->redirectToRoute('admin.bsv.index');
    }
}

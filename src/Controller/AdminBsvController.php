<?php

namespace App\Controller;

use App\Entity\Bsv;
use App\Form\BsvSendType;
use App\Form\BsvType;
use App\Repository\BsvRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            //Add Files
            $firstFile = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile = $form->get('third_file')->getData();

            if ($firstFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $firstFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $bsv->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $originalFilename = pathinfo($secondFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $secondFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $secondFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $bsv->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $originalFilename = pathinfo($thirdFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thirdFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $thirdFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $bsv->setThirdFile($newFilename);
            }

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
 * @Route("/admin/bsv/edit/{id}", name="admin.bsv.edit", methods="GET|POST")
 * @param Bsv $bsv
 * @param Request $request
 * @return Response
 */
    public function edit(Bsv $bsv, Request $request): Response
    {
        $form = $this->createForm(BsvType::class, $bsv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'BSV modifié avec succès');
            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/edit.html.twig', [
            'bsv' => $bsv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/bsv/send/{id}", name="admin.bsv.send", methods="GET|POST")
     * @param Bsv $bsv
     * @param Request $request
     * @return Response
     */
    public function send(Bsv $bsv, Request $request): Response
    {
        $form = $this->createForm(BsvSendType::class, $bsv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bsv->setSent(1);
            $this->em->flush();
            $this->addFlash('success', 'BSV envoyé avec succès');
            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/send.html.twig', [
            'bsv' => $bsv,
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

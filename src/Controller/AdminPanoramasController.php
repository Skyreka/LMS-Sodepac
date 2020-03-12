<?php

namespace App\Controller;

use App\Entity\Panoramas;
use App\Repository\PanoramasRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminPanoramasController extends AbstractController
{
    /**
     * @var PanoramasRepository
     */
    private $repositoryPanoramas;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PanoramasRepository $repository, ObjectManager $em)
    {
        $this->repositoryPanoramas = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/panoramas", name="admin.panoramas.index")
     * @return Response
     */
    public function index(): Response
    {
        $panoramas = $this->repositoryPanoramas->findAllNotSent();
        return $this->render('admin/panoramas/index.html.twig', [
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/admin/panoramas/{id}", name="admin.panoramas.delete", methods="DELETE")
     * @param Panoramas $panoramas
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Panoramas $panoramas, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $panoramas->getId(), $request->get('_token'))) {
            $this->em->remove($panoramas);
            $this->em->flush();
            $this->addFlash('success', 'Panorama supprimé avec succès');
        }

        return $this->redirectToRoute('admin.panoramas.index');
    }

}

<?php

namespace App\Controller;

use App\Entity\Panoramas;
use App\Form\PanoramaType;
use App\Repository\PanoramasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TechnicianPanoramasController extends AbstractController
{
    /**
     * @var PanoramasRepository
     */
    private $panoramasRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PanoramasRepository $panoramasRepository, EntityManagerInterface $em)
    {
        $this->panoramasRepository = $panoramasRepository;
        $this->em = $em;
    }

    /**
     * @Route("technician/panoramas", name="technician.panoramas.index")
     */
    public function index(): Response
    {
        $panoramas = $this->panoramasRepository->findAllPanoramasOfTechnician( $this->getUser()->getId() );
        return $this->render('technician/panoramas/index.html.twig', [
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/technician/panoramas/new", name="technician.panoramas.new")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $panorama = new Panoramas();
        $form = $this->createForm(PanoramaType::class, $panorama);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Add Files
            $firstFile = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile = $form->get('third_file')->getData();

            if ($firstFile) {
                $newFilename = uniqid() . '.' . $firstFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $firstFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $newFilename = uniqid() . '.' . $secondFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $secondFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $newFilename = uniqid() . '.' . $thirdFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $thirdFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setThirdFile($newFilename);
            }

            $datetime = New \DateTime();
            $panorama->setCreationDate( $datetime );
            //* TO DO (remove setter (default value))
            $panorama->setSent( 0 );
            $panorama->setValidate( 0 );
            $panorama->setTechnician($this->getUser());
            $this->em->persist($panorama);
            $this->em->flush();


            $this->addFlash('success', 'Panorama crée avec succès');

            return $this->redirectToRoute('technician.panoramas.index');
        }

        return $this->render('technician/panoramas/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
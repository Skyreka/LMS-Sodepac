<?php

namespace App\Controller;

use App\AsyncMethodService;
use App\Entity\Panoramas;
use App\Entity\PanoramaUser;
use App\Entity\Users;
use App\Form\PanoramaSendType;
use App\Form\PanoramaType;
use App\Service\EmailNotifier;
use App\Repository\PanoramasRepository;
use App\Repository\PanoramaUserRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panorama")
 */
class PanoramasController extends AbstractController
{
    /**
     * @var PanoramasRepository
     */
    private $repositoryPanoramas;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PanoramasRepository $repository, EntityManagerInterface $em)
    {
        $this->repositoryPanoramas = $repository;
        $this->em = $em;
    }

    /**
     * @Route("", name="panorama_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $panoramas = $this->repositoryPanoramas->findAllNotDeleted();
        if ( $this->getUser()->getStatus() === 'ROLE_TECHNICIAN') {
            $panoramas = $this->repositoryPanoramas->findAllNotDeletedByTechnician($this->getUser());
        }
        return $this->render('panoramas/index.html.twig', [
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/delete/{id}", name="panorama_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Panoramas $panoramas
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Panoramas $panoramas, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $panoramas->getId(), $request->get('_token'))) {
            $this->em->remove( $panoramas );
            $this->em->flush();
            $this->addFlash('success', 'Panorama supprimé avec succès');
        }

        return $this->redirectToRoute('panorama_index');
    }

    /**
     * @Route("/valid/{id}", name="panorama_valid", methods="VALID", requirements={"id":"\d+"})
     * @param Panoramas $panoramas
     * @param Request $request
     * @return RedirectResponse
     */
    public function valid(Panoramas $panoramas, Request $request)
    {
        if ($this->isCsrfTokenValid('valid' . $panoramas->getId(), $request->get('_token'))) {
            $panoramas->setValidate(1);
            $this->em->flush();
            $this->addFlash('success', 'Panorama validé avec succès');
        }

        return $this->redirectToRoute('panorama_index');
    }

    /**
     * @Route("/new", name="panorama_new", methods={"GET", "POST"})
     * @param Request $request
     * @param UsersRepository $ur
     * @param AsyncMethodService $asyncMethodService
     * @return Response
     */
    public function new(
        Request $request,
        UsersRepository $ur,
        AsyncMethodService $asyncMethodService
    ): Response
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
            $datetime = New DateTime();
            $panorama->setCreationDate( $datetime );
            //* TO DO (remove setter (default value))
            $panorama->setSent( 0 );
            $panorama->setValidate( 0 );
            $panorama->setOwner($this->getUser());
            $this->em->persist($panorama);
            $this->em->flush();

            //Envoie de mail aux admins
            $admins = $ur->findAllByRole('ROLE_ADMIN');
            foreach ($admins as $admin) {
                //Send Email to user
                $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $admin->getId(),
                    'params' => [
                        'subject' => 'Un panorama est en attente de validation - LMS Sodepac.',
                        'title' => 'Un panorama est en attente de validation.'
                    ]
                ]);
            }

            $this->addFlash('success', 'Panorama crée avec succès');
            return $this->redirectToRoute('panorama_index');
        }

        return $this->render('panoramas/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="panorama_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Panoramas $panoramas
     * @param Request $request
     * @return Response
     */
    public function edit(Panoramas $panoramas, Request $request): Response
    {
        $form = $this->createForm(PanoramaType::class, $panoramas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Add Files
            $firstFile = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile = $form->get('third_file')->getData();

            if ($firstFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();
                try {
                    $firstFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $panoramas->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $originalFilename = pathinfo($secondFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();


                try {
                    $secondFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $panoramas->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $originalFilename = pathinfo($thirdFile->getClientOriginalName(), PATHINFO_FILENAME);

                try {
                    $thirdFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $panoramas->setThirdFile($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success', 'Panorama modifié avec succès');
            return $this->redirectToRoute('panorama_index');
        }

        return $this->render('panoramas/edit.html.twig', [
            'panoramas' => $panoramas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/send/{id}", name="panorama_send", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Panoramas $panoramas
     * @param Request $request
     * @param AsyncMethodService $asyncMethodService
     * @return Response
     */
    public function send(
        Panoramas $panoramas,
        Request $request,
        AsyncMethodService $asyncMethodService
    ): Response
    {
        $form = $this->createForm(PanoramaSendType::class);
        $form->handleRequest($request);

        //Submit form
        if ($form->isSubmitted() && $form->isValid()) {
            $today = new DateTime();

            foreach ($form->get('customers')->getData() as $customer) {
                $displayAt = $form->get('display_at')->getData();
                // Relation
                $relation = new PanoramaUser();
                $relation->setPanorama($panoramas);
                $relation->setCustomers($customer);
                $relation->setSender( $this->getUser() );
                if ( $displayAt !== null ) {
                    $displayAt->setTime(8,00);
                    $relation->setDisplayAt($displayAt);
                } else {
                    $relation->setDisplayAt( $today );
                }

                //Send email notification Async
                $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $customer->getId(),
                    'params' => [
                        'subject' => 'Un nouveau panorama disponible sur LMS-Sodepac',
                        'title' => 'Un nouveau panorama disponible sur LMS-Sodepac',
                        'text1' => 'Un nouveau panorama est disponible sur votre application'
                    ]
                ]);

                $this->em->persist( $relation );
                $this->em->flush();
            }
            $this->addFlash('success', 'Panorama envoyé avec succès');

            return $this->redirectToRoute('panorama_index');
        }

        return $this->render('panoramas/send.html.twig', [
            'panoramas' => $panoramas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}", name="panorama_user_check", methods="CHECK", requirements={"id":"\d+"})
     * @param PanoramaUser $panoramaUser
     * @param Request $request
     * @return RedirectResponse
     */
    public function check(PanoramaUser $panoramaUser, Request $request)
    {
        if ($this->isCsrfTokenValid('check' . $panoramaUser->getId(), $request->get('_token'))) {
            $panoramaUser->setChecked(1);
            $this->em->flush();
        }

        return $this->redirectToRoute('panorama_user_history_index');
    }


    /**
     * @Route("/panoramas/history", name="panorama_history_index", methods={"GET"})
     * @return Response
     */
    public function history(): Response
    {
        return $this->render('panoramas/history/index.html.twig');
    }

    /**
     * @Route("/history/{year}", name="panorama_history_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     * @param PanoramaUserRepository $pur
     * @param $year
     * @return Response
     */
    public function list(PanoramaUserRepository $pur, $year): Response
    {
        $user = $this->getUser();
        if ($user->getStatus() == 'ROLE_ADMIN') {
            $panoramas = $pur->findAllByYear($year);
        } else {
            $panoramas = $pur->findAllByYearAndSender($year, $this->getUser());
        }
        return $this->render('panoramas/history/show.html.twig', [
            'panoramas' => $panoramas,
            'year' => $year
        ]);
    }

    /**
     * @Route("/user/history", name="panorama_user_history_index", methods={"GET"})
     * @param PanoramaUserRepository $pur
     * @return Response
     * @throws \Exception
     */
    public function userHistory(PanoramaUserRepository $pur): Response
    {
        $year = date('Y');
        $panoramas = $pur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('panoramas/history/user/index.html.twig',[
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/user/history/{year}", name="panorama_user_history_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     * @param PanoramaUserRepository $pur
     * @param $year
     * @return Response
     */
    public function userList(PanoramaUserRepository $pur, $year): Response
    {
        $panoramas = $pur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('panoramas/history/user/history_show.html.twig', [
            'panoramas' => $panoramas,
            'year' => $year
        ]);
    }

    /**
     * @Route("/user/panorama/show/{id}", name="user_panorama_show", methods={"GET"})
     * @param Panoramas $panorama
     * @return Response
     */
    public function userShow( Panoramas $panorama ): Response
    {
        return $this->render('panoramas/history/user/show.html.twig',[
            'panorama' => $panorama
        ]);
    }
}

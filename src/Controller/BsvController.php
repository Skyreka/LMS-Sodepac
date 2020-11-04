<?php

namespace App\Controller;


use App\Entity\Bsv;
use App\Entity\BsvUsers;
use App\Form\BsvSendType;
use App\Form\BsvType;
use App\Repository\BsvRepository;
use App\Repository\BsvUsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin/bsv")
 */
class BsvController extends AbstractController
{
    /**
     * @var BsvRepository
     */
    private $repositoryBsv;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(BsvRepository $repository, EntityManagerInterface $em)
    {
        $this->repositoryBsv = $repository;
        $this->em = $em;
    }

    /**
     * @Route("", name="admin_bsv_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $bsv = $this->repositoryBsv->findAllNotDeleted();
        return $this->render('admin/bsv/index.html.twig', [
            'bsv' => $bsv
        ]);
    }

    /**
     * @Route("/new", name="admin_bsv_new", methods={"GET"})
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
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();
                try {
                    $firstFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();

                try {
                    $secondFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();

                try {
                    $thirdFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setThirdFile($newFilename);
            }

            $datetime = New \DateTime();
            $bsv->setCreationDate( $datetime );
            //* TO DO (remove setter (default value))
            $bsv->setSent( 0 );
            $this->em->persist($bsv);
            $this->em->flush();


            $this->addFlash('success', 'Flash crée avec succès');

            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
 * @Route("edit/{id}", name="admin_bsv_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
 * @param Bsv $bsv
 * @param Request $request
 * @return Response
 */
    public function edit(Bsv $bsv, Request $request): Response
    {
        $form = $this->createForm(BsvType::class, $bsv);
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
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();

                try {
                    $secondFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();

                try {
                    $thirdFile->move(
                        $this->getParameter('bsv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $bsv->setThirdFile($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success', 'Flash modifié avec succès');
            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/edit.html.twig', [
            'bsv' => $bsv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/send/{id}", name="admin_bsv_send", methods={"GET","POST"}, requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function send(Bsv $bsv, Request $request): Response
    {
        $bsvUsers = new BsvUsers();
        $form = $this->createForm(BsvSendType::class, $bsvUsers);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->all();
            $customers = $data['user']->getData();
            $displayAt = $data['display_at']->getData();
            //-- Init
            $datetime = New \DateTime();
            //-- Update BSV info
            $bsv->setSendDate( $datetime );
            //-- Create relation
            foreach ($customers as $customer) {
                $relation = new BsvUsers();
                $this->em->persist($relation);
                $relation->setBsv($bsv);
                $relation->setCustomers($customer);
                $relation->setChecked(0);
                if ( $displayAt !== null ) {
                    $displayAt->setTime(8,00);
                    $relation->setDisplayAt($displayAt);
                } else {
                    $relation->setDisplayAt($datetime);
                }
            }
            $this->em->flush();
            $this->addFlash('success', 'Flash envoyé avec succès');
            return $this->redirectToRoute('admin.bsv.index');
        }

        return $this->render('admin/bsv/send.html.twig', [
            'bsv' => $bsv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bsv_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Bsv $bsv, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $bsv->getId(), $request->get('_token'))) {
            $bsv->setArchive(1);
            $this->em->flush();
            $this->addFlash('success', 'Flash supprimé avec succès');
        }

        return $this->redirectToRoute('admin.bsv.index');
    }

    /**
     * @Route("/history", name="admin_bsv_history_index", methods={"GET"})
     * @return Response
     */
    public function history(): Response
    {
        return $this->render('admin/bsv/history/index.html.twig');
    }

    /**
     * @Route("/admin/bsv/history/{year}", name="admin.bsv.history.show", methods={"GET"}, requirements={"year":"\d+"})
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function list(BsvUsersRepository $bur, $year): Response
    {
        $bsv = $bur->findAllByYear($year);
        return $this->render('admin/bsv/history/show.html.twig', [
            'bsv' => $bsv,
            'year' => $year
        ]);
    }

    /**
     * @Route("/user/bsv/{id}", name="user.bsv.check", methods="CHECK", requirements={"id":"\d+"})
     * @param BsvUsers $bsvUsers
     * @param Request $request
     * @return RedirectResponse
     */
    public function check(BsvUsers $bsvUsers, Request $request)
    {
        if ($this->isCsrfTokenValid('check' . $bsvUsers->getId(), $request->get('_token'))) {
            $bsvUsers->setChecked(1);
            $this->em->flush();
        }

        return $this->redirectToRoute('user.bsv.history.index');
    }

    /**
     * @Route("/user/bsv/history", name="user.bsv.history.index", methods={"GET"})
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function userHistory(BsvUsersRepository $bur): Response
    {
        $year = date('Y');
        $bsv = $bur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('admin/bsv/history/user/index.html.twig',[
            'bsv' => $bsv
        ]);
    }

    /**
     * @Route("/user/bsv/history/{year}", name="user.bsv.history.show")
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function userList(BsvUsersRepository $bur, $year): Response
    {
        $bsv = $bur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('admin/bsv/history/user/show.html.twig', [
            'bsv' => $bsv,
            'year' => $year
        ]);
    }

    /**
     * @Route("/technician/bsv/history", name="technician.bsv.history.index")
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function technicianHistory(BsvUsersRepository $bur): Response
    {
        $year = date('Y');
        $bsv = $bur->findAllByYearAndTechnician($year, $this->getUser()->getId());
        return $this->render('admin/bsv/history/technician/index.html.twig',[
            'bsv' => $bsv
        ]);
    }

    /**
     * @Route("/technician/bsv/history/{year}", name="technician.bsv.history.show")
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function technicianList(BsvUsersRepository $bur, $year): Response
    {
        $bsv = $bur->findAllByYearAndTechnician($year, $this->getUser()->getId());
        return $this->render('admin/bsv/history/technician/show.html.twig', [
            'bsv' => $bsv
        ]);
    }
}

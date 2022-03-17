<?php

namespace App\Controller;

use App\AsyncMethodService;
use App\Entity\Bsv;
use App\Entity\BsvUsers;
use App\Form\FlashSendType;
use App\Form\FlashType;
use App\Repository\BsvRepository;
use App\Repository\BsvUsersRepository;
use App\Repository\UsersRepository;
use App\Service\EmailNotifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class FlashController
 * @package App\Controller
 */
class FlashController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param BsvRepository $bsv
     * @return Response
     * @Route("admin/flash", name="admin_flash_index", methods={"GET"})
     */
    public function adminIndex( BsvRepository $bsv ): Response
    {
        $flashs = $bsv->findAllNotDeleted();
        return $this->render('flash/admin/index.html.twig', [
            'flashs' => $flashs
        ]);
    }

    /**
     * @Route("admin/flash/new", name="admin_flash_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function adminNew(Request $request): Response
    {
        $flash = new Bsv();
        $form = $this->createForm(FlashType::class, $flash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Add Files
            $firstFile = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile = $form->get('third_file')->getData();

            if ($firstFile) {
                $newFilename = uniqid() . '.' . $firstFile->guessExtension();
                try {
                    $firstFile->move(
                        $this->getParameter('flash_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $flash->setFirstFile($newFilename);
            }

            if ($secondFile) {
                $newFilename = uniqid() . '.' . $secondFile->guessExtension();

                try {
                    $secondFile->move(
                        $this->getParameter('flash_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $flash->setSecondFile($newFilename);
            }

            if ($thirdFile) {
                $newFilename = uniqid() . '.' . $thirdFile->guessExtension();

                try {
                    $thirdFile->move(
                        $this->getParameter('flash_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $flash->setThirdFile($newFilename);
            }

            $flash->setCreationDate( new \DateTime() );
            $this->em->persist($flash);
            $this->em->flush();
            $this->addFlash('success', 'Flash crée avec succès');
            return $this->redirectToRoute('admin_flash_index');
        }

        return $this->render('flash/admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/flash/edit/{id}", name="admin_flash_edit", methods={"GET","POST"}, requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @return Response
     */
    public function adminEdit(Bsv $bsv, Request $request): Response
    {
        $form = $this->createForm(FlashType::class, $bsv);
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
            return $this->redirectToRoute('admin_flash_index');
        }

        return $this->render('flash/admin/edit.html.twig', [
            'bsv' => $bsv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/flash/send/{id}", name="admin_flash_send", methods={"GET","POST"}, requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @param AsyncMethodService $asyncMethodService
     * @return Response
     */
    public function adminSend(
        Bsv $bsv,
        Request $request,
        AsyncMethodService $asyncMethodService
    ): Response
    {
        $bsvUsers = new BsvUsers();
        $form = $this->createForm(FlashSendType::class, $bsvUsers);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->all();
            $customers = $data['user']->getData();
            $displayAt = $data['display_at']->getData();
            //-- Init
            $datetime = new \DateTime();
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

                //Send email notification Async
                $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $customer->getId(),
                    'params' => [
                        'subject' => 'Un nouveau flash est disponible sur LMS-Sodepac',
                        'text' => 'En nouveau flash est disponible. Vous pouvez le consulter sur votre application'
                    ]
                ]);
            }
            $bsv->setSent(1);
            $this->em->flush();
            $this->addFlash('success', 'Flash envoyé avec succès');
            return $this->redirectToRoute('admin_flash_index');
        }

        return $this->render('flash/admin/send.html.twig', [
            'bsv' => $bsv,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/flash/send/all/{id}", name="admin_flash_send_all", methods="SEND", requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @param AsyncMethodService $asyncMethodService
     * @param UsersRepository $ur
     * @return RedirectResponse
     */
    public function adminSendAll(
        Bsv $bsv,
        Request $request,
        AsyncMethodService $asyncMethodService,
        UsersRepository $ur
    ): RedirectResponse
    {
        if ($this->isCsrfTokenValid('send' . $bsv->getId(), $request->get('_token'))) {
            $customers = $ur->findAllByRole('ROLE_USER');
            foreach ($customers as $customer) {
                $relation = new BsvUsers();
                $this->em->persist($relation);
                $relation
                    ->setBsv($bsv)
                    ->setCustomers($customer)
                    ->setChecked(1)
                    ->setDisplayAt(new \DateTime());

                //Send email notification
                $asyncMethodService->async(EmailNotifier::class, 'notify', [ 'userId' => $customer->getId(),
                    'params' => [
                        'subject' => 'Un nouveau flash est disponible sur LMS-Sodepac',
                        'text' => ''
                    ]
                ]);
            }
            $bsv->setSent(1);
            $this->em->flush();
            $this->addFlash('success', 'Flash envoyé avec succès');
        }

        return $this->redirectToRoute('admin_flash_index');
    }

    /**
     * @Route("admin/flash/delete/{id}", name="admin_flash_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param Bsv $bsv
     * @param Request $request
     * @return RedirectResponse
     */
    public function adminDelete(Bsv $bsv, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $bsv->getId(), $request->get('_token'))) {
            $this->em->remove( $bsv );
            $this->em->flush();
            $this->addFlash('success', 'Flash supprimé avec succès');
        }

        return $this->redirectToRoute('admin_flash_index');
    }

    /**
     * @Route("admin/flash/history", name="admin_flash_history_index", methods={"GET"})
     * @return Response
     */
    public function adminHistory(): Response
    {
        return $this->render('flash/admin/history/index.html.twig');
    }

    /**
     * @Route("admin/flash/history/{year}", name="admin_flash_history_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function adminList(BsvUsersRepository $bur, $year): Response
    {
        $flashs = $bur->findAllByYear($year);
        return $this->render('flash/admin/history/show.html.twig', [
            'flashs' => $flashs,
            'year' => $year
        ]);
    }

    /**
     * @Route("flash/check/{id}", name="user_flash_check", methods="CHECK", requirements={"id":"\d+"})
     * @param BsvUsers $bsvUsers
     * @param Request $request
     * @return RedirectResponse
     */
    public function userCheck(BsvUsers $bsvUsers, Request $request)
    {
        if ($this->isCsrfTokenValid('check' . $bsvUsers->getId(), $request->get('_token'))) {
            $bsvUsers->setChecked(1);
            $this->em->flush();
        }

        return $this->redirectToRoute('user_flash_index');
    }

    /**
     * @Route("flash", name="user_flash_index", methods={"GET"})
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function userIndex(BsvUsersRepository $bur): Response
    {
        $currentYear = date('Y');
        $flashs = $bur->findAllByYearAndCustomer($currentYear, $this->getUser()->getId());
        return $this->render('flash/user/index.html.twig',[
            'flashs' => $flashs
        ]);
    }

    /**
     * @Route("flash/show/{id}", name="user_flash_show", methods={"GET"})
     * @param Bsv $bsv
     * @return Response
     */
    public function userShow( Bsv $bsv ): Response
    {
        return $this->render('flash/user/show.html.twig',[
            'bsv' => $bsv
        ]);
    }

    /**
     * @Route("flash/{year}", name="user_flash_history_show", methods={"GET","POST"}, requirements={"year":"\d+"})
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function userHistoryShow(BsvUsersRepository $bur, $year): Response
    {
        $flashs = $bur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('flash/user/history_show.html.twig', [
            'flashs' => $flashs,
            'year' => $year
        ]);
    }

    /**
     * @Route("technician/flash", name="technician_flash_index", methods={"GET"})
     * @param BsvUsersRepository $bur
     * @return Response
     */
    public function technicianIndex(BsvUsersRepository $bur): Response
    {
        $year = date('Y');
        $flashs = $bur->findAllByYearAndTechnician($year, $this->getUser()->getId());
        return $this->render('flash/technician/index.html.twig',[
            'flashs' => $flashs
        ]);
    }

    /**
     * @Route("technician/flash/{year}", name="technician_flash_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     * @param BsvUsersRepository $bur
     * @param $year
     * @return Response
     */
    public function technicianShow(BsvUsersRepository $bur, $year): Response
    {
        $flashs = $bur->findAllByYearAndTechnician($year, $this->getUser()->getId());
        return $this->render('flash/technician/show.html.twig', [
            'flashs' => $flashs,
            'year' => $year
        ]);
    }
}

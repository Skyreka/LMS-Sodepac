<?php

namespace App\Http\Controller;

use App\AsyncMethodService;
use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Panorama\Entity\Panorama;
use App\Domain\Panorama\Entity\PanoramaSend;
use App\Domain\Panorama\Form\PanoramaSendType;
use App\Domain\Panorama\Form\PanoramaType;
use App\Domain\Panorama\Repository\PanoramaRepository;
use App\Domain\Panorama\Repository\PanoramaSendRepository;
use App\Service\EmailNotifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panorama", name="panorama_")
 */
class PanoramaController extends AbstractController
{
    public function __construct(
        private readonly PanoramaRepository $repositoryPanorama,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $panorama = $this->repositoryPanorama->findAllNotDeleted();
        if($this->getUser()->getStatus() === 'ROLE_TECHNICIAN') {
            $panorama = $this->repositoryPanorama->findAllNotDeletedByTechnician($this->getUser());
        }
        return $this->render('panorama/index.html.twig', [
            'panorama' => $panorama
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods="DELETE", requirements={"id":"\d+"})
     */
    public function delete(Panorama $panorama, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $panorama->getId(), $request->get('_token'))) {
            $this->em->remove($panorama);
            $this->em->flush();
            $this->addFlash('success', 'Panorama supprimé avec succès');
        }
        
        return $this->redirectToRoute('panorama_index');
    }
    
    /**
     * @Route("/valid/{id}", name="valid", methods="VALID", requirements={"id":"\d+"})
     */
    public function valid(Panorama $panorama, Request $request)
    {
        if($this->isCsrfTokenValid('valid' . $panorama->getId(), $request->get('_token'))) {
            $panorama->setValidate(1);
            $this->em->flush();
            $this->addFlash('success', 'Panorama validé avec succès');
        }
        
        return $this->redirectToRoute('panorama_index');
    }
    
    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        UsersRepository $ur,
        AsyncMethodService $asyncMethodService
    ): Response
    {
        $panorama = new Panorama();
        $form     = $this->createForm(PanoramaType::class, $panorama);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //Add Files
            $firstFile  = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile  = $form->get('third_file')->getData();
            
            if($firstFile) {
                $newFilename = uniqid() . '.' . $firstFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $firstFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setFirstFile($newFilename);
            }
            
            if($secondFile) {
                $newFilename = uniqid() . '.' . $secondFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $secondFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setSecondFile($newFilename);
            }
            
            if($thirdFile) {
                $newFilename = uniqid() . '.' . $thirdFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $thirdFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $panorama->setThirdFile($newFilename);
            }
            $datetime = new DateTime();
            $panorama->setCreationDate($datetime);
            //* TO DO (remove setter (default value))
            $panorama->setSent(0);
            $panorama->setValidate(0);
            $panorama->setOwner($this->getUser());
            $this->em->persist($panorama);
            $this->em->flush();
            
            //Envoie de mail aux admins
            $admins = $ur->findAllByRole('ROLE_ADMIN');
            foreach($admins as $admin) {
                //Send Email to user
                $asyncMethodService->async(EmailNotifier::class, 'notify', ['userId' => $admin->getId(),
                    'params' => [
                        'subject' => 'Un panorama est en attente de validation - ' . $this->getParameter('APP_NAME'),
                        'title' => 'Un panorama est en attente de validation.'
                    ]
                ]);
            }
            
            $this->addFlash('success', 'Panorama crée avec succès');
            return $this->redirectToRoute('panorama_index');
        }
        
        return $this->render('panorama/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Panorama $panorama, Request $request): Response
    {
        $form = $this->createForm(PanoramaType::class, $panorama);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //Add Files
            $firstFile  = $form->get('first_file')->getData();
            $secondFile = $form->get('second_file')->getData();
            $thirdFile  = $form->get('third_file')->getData();
            
            if($firstFile) {
                $originalFilename = pathinfo($firstFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename      = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();
                try {
                    $firstFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                }
                $panorama->setFirstFile($newFilename);
            }
            
            if($secondFile) {
                $originalFilename = pathinfo($secondFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename      = $originalFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();
                
                
                try {
                    $secondFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                }
                $panorama->setSecondFile($newFilename);
            }
            
            if($thirdFile) {
                $originalFilename = pathinfo($thirdFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                try {
                    $thirdFile->move(
                        $this->getParameter('panorama_directory'),
                        $newFilename
                    );
                } catch(FileException $e) {
                }
                $panorama->setThirdFile($newFilename);
            }
            $this->em->flush();
            $this->addFlash('success', 'Panorama modifié avec succès');
            return $this->redirectToRoute('panorama_index');
        }
        
        return $this->render('panorama/edit.html.twig', [
            'panorama' => $panorama,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/send/{id}", name="send", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function send(
        Panorama $panorama,
        Request $request,
        AsyncMethodService $asyncMethodService
    ): Response
    {
        $form = $this->createForm(PanoramaSendType::class);
        $form->handleRequest($request);
        
        //Submit form
        if($form->isSubmitted() && $form->isValid()) {
            $today = new DateTime();
            
            foreach($form->get('customers')->getData() as $customer) {
                $displayAt = $form->get('display_at')->getData();
                // Relation
                $relation = new PanoramaSend();
                $relation->setPanorama($panorama);
                $relation->setCustomers($customer);
                $relation->setSender($this->getUser());
                
                if($displayAt !== null) {
                    $displayAt->setTime(8, 00);
                    $relation->setDisplayAt($displayAt);
                } else {
                    $relation->setDisplayAt($today);
                }
                
                //Send email notification Async
                $asyncMethodService->async(EmailNotifier::class, 'notify', ['userId' => $customer->getId(),
                    'params' => [
                        'subject' => 'Un nouveau panorama disponible sur ' . $this->getParameter('APP_NAME'),
                        'title' => 'Un nouveau panorama disponible sur ' . $this->getParameter('APP_NAME'),
                        'text1' => 'Un nouveau panorama est disponible sur votre application'
                    ]
                ]);
                
                $this->em->persist($relation);
                $this->em->flush();
            }
            $this->addFlash('success', 'Panorama envoyé avec succès');
            
            return $this->redirectToRoute('panorama_index');
        }
        
        return $this->render('panorama/send.html.twig', [
            'panorama' => $panorama,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/user/{id}", name="user_check", methods="CHECK", requirements={"id":"\d+"})
     */
    public function check(PanoramaSend $panoramaUser, Request $request)
    {
        if($this->isCsrfTokenValid('check' . $panoramaUser->getId(), $request->get('_token'))) {
            $panoramaUser->setChecked(1);
            $this->em->flush();
        }
        
        return $this->redirectToRoute('panorama_user_history_index');
    }
    
    
    /**
     * @Route("/history", name="history_index", methods={"GET"})
     */
    public function history(): Response
    {
        return $this->render('panorama/history/index.html.twig');
    }
    
    /**
     * @Route("/history/{year}", name="history_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     */
    public function list(PanoramaSendRepository $pur, string $year): Response
    {
        $user = $this->getUser();
        if($user->getStatus() == 'ROLE_ADMIN') {
            $panorama = $pur->findAllByYear($year);
        } else {
            $panorama = $pur->findAllByYearAndSender($year, $this->getUser());
        }
        return $this->render('panorama/history/show.html.twig', [
            'panorama' => $panorama,
            'year' => $year
        ]);
    }
    
    /**
     * @Route("/user/history", name="user_history_index", methods={"GET"})
     */
    public function userHistory(PanoramaSendRepository $pur): Response
    {
        $year     = date('Y');
        $panorama = $pur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('panorama/history/user/index.html.twig', [
            'panorama' => $panorama
        ]);
    }
    
    /**
     * @Route("/user/history/{year}", name="user_history_show", methods={"GET", "POST"}, requirements={"year":"\d+"})
     */
    public function userList(PanoramaSendRepository $pur, string $year): Response
    {
        $panorama = $pur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('panorama/history/user/history_show.html.twig', [
            'panorama' => $panorama,
            'year' => $year
        ]);
    }
    
    /**
     * @Route("/user/panorama/show/{id}", name="user_show", methods={"GET"})
     * @param Panorama $panorama
     * @return Response
     */
    public function userShow(Panorama $panorama): Response
    {
        return $this->render('panorama/history/user/show.html.twig', [
            'panorama' => $panorama
        ]);
    }
}

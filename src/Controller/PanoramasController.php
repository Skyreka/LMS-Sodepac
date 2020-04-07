<?php

namespace App\Controller;

use App\Entity\Panoramas;
use App\Entity\PanoramaUser;
use App\Entity\Users;
use App\Form\PanoramaSendType;
use App\Form\PanoramaType;
use App\Repository\PanoramasRepository;
use App\Repository\PanoramaUserRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PanoramasController extends AbstractController
{
    /**
     * @var PanoramasRepository
     */
    private $repositoryPanoramas;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PanoramasRepository $repository, EntityManagerInterface $em)
    {
        $this->repositoryPanoramas = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/panoramas", name="panoramas.index")
     * @return Response
     */
    public function index(): Response
    {
        $panoramas = $this->repositoryPanoramas->findAllNotSent();
        return $this->render('panoramas/index.html.twig', [
            'panoramas' => $panoramas
        ]);
    }

    /**
     * @Route("/panoramas/{id}", name="panoramas.delete", methods="DELETE")
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

        return $this->redirectToRoute('panoramas.index');
    }

    /**
     * @Route("/panoramas/{id}", name="panoramas.valid", methods="VALID")
     * @param Panoramas $panoramas
     * @param Request $request
     * @return RedirectResponse
     */
    public function valid(Panoramas $panoramas, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $panoramas->getId(), $request->get('_token'))) {
            $panoramas->setValidate(1);
            $this->em->flush();
            $this->addFlash('success', 'Panorama validé avec succès');
        }

        return $this->redirectToRoute('panoramas.index');
    }

    /**
     * @Route("/panoramas/new", name="panoramas.new")
     * @param Request $request
     * @param UsersRepository $ur
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, UsersRepository $ur, \Swift_Mailer $mailer): Response
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
            $this->em->persist($panorama);
            $this->em->flush();

            //Envoie de mail aux admins
            $admins = $ur->findAllByRole('ROLE_ADMIN');
            foreach ($admins as $admin) {
                $pathInfo = '/panoramas';
                $link = $request->getUriForPath($pathInfo);
                $message = (new \Swift_Message('Un panorama est en attente de validation.'))
                    ->setFrom('send@lms-sodepac.fr')
                    ->setTo( $admin->getEmail() )
                    ->setBody(
                        $this->renderView(
                            'emails/panorama.html.twig', [
                                'first_name' => $admin->getFirstname(),
                                'link' => $link
                            ]
                        ),
                        'text/html'
                    )
                ;
                $mailer->send($message);
            }

            $this->addFlash('success', 'Panorama crée avec succès');
            return $this->redirectToRoute('panoramas.index');
        }

        return $this->render('panoramas/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/panorama/edit/{id}", name="panoramas.edit", methods="GET|POST")
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
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $firstFile->guessExtension();
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
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $secondFile->guessExtension();

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
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $thirdFile->guessExtension();

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
            return $this->redirectToRoute('panoramas.index');
        }

        return $this->render('panoramas/edit.html.twig', [
            'panoramas' => $panoramas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/panoramas/send/{id}", name="panoramas.send", methods="GET|POST")
     * @param Panoramas $panoramas
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function send(Panoramas $panoramas, Request $request): Response
    {
        //Set form
        $form = $this->createFormBuilder()
            ->add('display_at', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off'
                ],
                'label' => 'Date d\'envoi',
                'help' => 'Remplir uniquement en cas d\'envoi différé.'
            ])
            ->add('customers', EntityType::class, [
                'class' => Users::class,
                'choice_label' => function(Users $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'query_builder' => function (UsersRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.status', 'ASC')
                        ->andWhere('u.technician = :technician')
                        ->setParameter('technician', $this->getUser()->getId() );
                },
                'label'     => 'Envoyer à :',
                'expanded'  => true,
                'multiple'  => true,
            ])
            ->getForm();
        $form->handleRequest($request);

        //Submit form
        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = New DateTime();
            $data = $form->all();
            foreach ($data['customers']->getData() as $customer) {
                $displayAt = $data['display_at']->getData();
                $relation = new PanoramaUser();
                $this->em->persist($relation);
                $relation->setPanorama($panoramas);
                $relation->setCustomers($customer);
                $relation->setSender($this->getUser());
                if ( $displayAt !== null ) {
                    $displayAt->setTime(8,00);
                    $relation->setDisplayAt($displayAt);
                } else {
                    $relation->setDisplayAt($datetime);
                }
            }
            $this->em->flush();
            $this->addFlash('success', 'Panorama envoyé avec succès');
            return $this->redirectToRoute('panoramas.index');
        }

        return $this->render('panoramas/send.html.twig', [
            'panoramas' => $panoramas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/panorama/{id}", name="user.panorama.check", methods="CHECK")
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

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/panoramas/history", name="panoramas.history.index")
     * @return Response
     */
    public function history(): Response
    {
        return $this->render('panoramas/history/index.html.twig');
    }

    /**
     * @Route("/panoramas/history/{year}", name="panoramas.history.show")
     * @param PanoramaUserRepository $pur
     * @param $year
     * @return Response
     */
    public function list(PanoramaUserRepository $pur, $year): Response
    {
        $user = $this->getUser();
        if ($user->getRoles() == 'ROLE_ADMIN') {
            $panoramas = $this->repositoryPanoramas->findAllByYear($year);
        } else {
            $panoramas = $pur->findAllByYearAndSender($year, $this->getUser());
        }
        return $this->render('panoramas/history/show.html.twig', [
            'panoramas' => $panoramas,
            'year' => $year
        ]);
    }

    /**
     * @Route("/user/panoramas/history", name="user.panoramas.history.index")
     * @param PanoramaUserRepository $pur
     * @return Response
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
     * @Route("/user/panoramas/history/{year}", name="user.panoramas.history.show")
     * @param PanoramaUserRepository $pur
     * @param $year
     * @return Response
     */
    public function userList(PanoramaUserRepository $pur, $year): Response
    {
        $panoramas = $pur->findAllByYearAndCustomer($year, $this->getUser()->getId());
        return $this->render('panoramas/history/user/show.html.twig', [
            'panoramas' => $panoramas,
            'year' => $year
        ]);
    }
}

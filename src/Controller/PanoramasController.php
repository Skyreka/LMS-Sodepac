<?php

namespace App\Controller;

use App\Entity\Panoramas;
use App\Entity\PanoramaUser;
use App\Entity\Users;
use App\Form\PanoramaSendType;
use App\Form\PanoramaType;
use App\Repository\PanoramasRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
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

    public function __construct(PanoramasRepository $repository, ObjectManager $em)
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
            $datetime = New DateTime();
            $panorama->setCreationDate( $datetime );
            //* TO DO (remove setter (default value))
            $panorama->setSent( 0 );
            $panorama->setValidate( 0 );
            $this->em->persist($panorama);
            $this->em->flush();
            $this->addFlash('success', 'Panorama crée avec succès');
            return $this->redirectToRoute('panoramas.index');
        }

        return $this->render('panoramas/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/panoramas/send/{id}", name="panoramas.send", methods="GET|POST")
     * @param Panoramas $panoramas
     * @param Request $request
     * @return Response
     * @throws Exception
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
            $panoramas->setSendDate( $datetime );
            $panoramas->setSent(1);
            foreach ($data['customers']->getData() as $customer) {
                $displayAt = $data['display_at']->getData();
                $relation = new PanoramaUser();
                $this->em->persist($relation);
                $relation->setPanorama($panoramas);
                $relation->setCustomers($customer);
                if ( $displayAt !== null ) {
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
}

<?php

namespace App\Controller\Admin;

use App\Entity\PPF;
use App\Entity\PPFInput;
use App\Form\PPF\PPFAddInput;
use App\Form\PPF\PPFStep1;
use App\Form\PPF\PPFStep2;
use App\Form\PPF\PPFStep3;
use App\Form\PPF\PPFStep4;
use App\Form\PPF\PPFUserSelect;
use App\Repository\InterventionsRepository;
use App\Repository\PPFInputRepository;
use App\Repository\PPFRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PPFController
 * @package App\Controller
 * @Route("/admin/ppf")
 */
class PPFController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PPFRepository
     */
    private $ppfRepository;

    public function __construct(EntityManagerInterface $em, PPFRepository $pr )
    {
        $this->em = $em;
        $this->ppfRepository = $pr;
    }

    /**
     * @Route("/user-select", name="ppf_user_select", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function userSelect( Request $request ): Response
    {
        $form = $this->createForm( PPFUserSelect::class );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Display error if user don't have exploitation
            if( $data->getExploitation() == NULL) {
                $this->addFlash('danger', 'Votre client n\'a aucune exploitation déclarée, veuillez modifier son compte pour pouvoir lui établir un catalogue');
                return $this->redirectToRoute('ppf_user_select');
            }

            return $this->redirectToRoute('ppf_step_1', ['exploitation' => $data->getExploitation()->getId()]);
        }

        return $this->render('admin/PPF/userSelect.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/step/1", name="ppf_step_1", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step1( Request $request ): Response
    {
        $form = $this->createForm( PPFStep1::class, null, ['exploitation' => $request->get('exploitation')] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Save to DB
            $ppf = new PPF();
            $ppf->setAddedDate( new \DateTime() );
            $ppf->setStatus( 1 );
            $ppf->setCulture( $data['culture'] );
            $ppf->setEffiencyPrev( $data['effiency_prev'] );
            $ppf->setQtyAzoteAddPrev( $data['qty_azote_add_prev'] );
            $ppf->setDateImplantationPlanned( $data['date_implantation_planned'] );

            $this->em->persist( $ppf );
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_2', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/step1.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/step/2", name="ppf_step_2", methods={"GET", "POST"})
     * @param Request $request
     * @param InterventionsRepository $ir
     * @return Response
     */
    public function step2( Request $request, InterventionsRepository $ir ): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Get objective of culture in intervention
        $intervention = $ir->findOneBy( ['culture' => $ppf->getCulture(), 'type' => 'Semis'] );

        // Form
        $form = $this->createForm( PPFStep2::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_3', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/step2.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf,
            'intervention' => $intervention
        ]);
    }

    /**
     * @Route("/step/3", name="ppf_step_3", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step3( Request $request): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $form = $this->createForm( PPFStep3::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_4', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/step3.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/step/4", name="ppf_step_4", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step4( Request $request): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $form = $this->createForm( PPFStep4::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/step4.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/step/5", name="ppf_step_5", methods={"GET", "POST"})
     * @param Request $request
     * @param PPFInputRepository $pir
     * @return Response
     */
    public function step5( Request $request, PPFInputRepository $pir ): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Get inputs of PPF
        $inputs = $pir->findBy( ['ppf' => $ppf ]);

        // Form
        $form = $this->createForm( PPFStep4::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ppf->setStatus( 2 );

            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/step5.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf,
            'inputs' => $inputs
        ]);
    }

    /**
     * @Route("/step/add-input", name="ppf_add_input", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function addInput( Request $request ): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $ppfInput = new PPFInput();
        $form = $this->createForm( PPFAddInput::class, $ppfInput );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ppfInput->setPpf( $ppf );

            // Save to DB
            $this->em->persist( $ppfInput );
            $this->em->flush();

            return $this->redirectToRoute('ppf_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/add_input.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/summary", name="ppf_summary", methods={"GET", "POST"})
     * @param Request $request
     * @param InterventionsRepository $ir
     * @param PPFInputRepository $pir
     * @return Response
     */
    public function summary( Request $request, InterventionsRepository $ir, PPFInputRepository $pir): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Get objective of culture in intervention
        $intervention = $ir->findOneBy( ['culture' => $ppf->getCulture(), 'type' => 'Semis'] );

        // Get inputs of PPF
        $inputs = $pir->findBy( ['ppf' => $ppf ]);

        return $this->render('admin/PPF/summary.html.twig', [
            'ppf' => $ppf,
            'intervention' => $intervention,
            'inputs' => $inputs
        ]);
    }

    /**
     * @Route("/", name="ppf_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/PPF/index.html.twig', [
            'ppfs' => $this->ppfRepository->findAll()
        ]);
    }

    /**
     * @Route("/new_data_ajax", name="ppf_select_data")
     * @param Request $request
     * @param UsersRepository $ur
     * @param Users $users
     * @return JsonResponse
     */
    public function selectData(Request $request, UsersRepository $ur): JsonResponse
    {
        //Get information from ajax call
        $term = $request->query->get('q');
        $limit = $request->query->get('page_limit');

        //Query of like call
        $users = $ur->createQueryBuilder('u')
            ->where('u.lastname LIKE :lastname')
            ->orWhere('u.firstname LIKE :firstname')
            ->setParameter('lastname', '%' . $term . '%')
            ->setParameter('firstname', '%' . $term . '%')
            ->andWhere('u.pack = :pack')
            ->setParameter('pack', 'PACK_FULL')
            ->setMaxResults( $limit )
            ->getQuery()
            ->getResult()
        ;

        // Return Array of key = id && text = value
        $array = [];
        foreach ($users as $user) {
            $array[] = array(
                'id' => $user->getExploitation()->getId(),
                'text' => $user->getIdentity()
            );
        }

        // Return JsonResponse of code 200
        return new JsonResponse( $array, 200);
    }
}

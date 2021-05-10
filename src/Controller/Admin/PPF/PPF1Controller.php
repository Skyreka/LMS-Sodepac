<?php

namespace App\Controller\Admin\PPF;

use App\Entity\PPF;
use App\Entity\PPFInput;
use App\Form\PPF\Sunflower\PPF1AddInput;
use App\Form\PPF\Sunflower\PPF1Step1;
use App\Form\PPF\Sunflower\PPF1Step2;
use App\Form\PPF\Sunflower\PPF1Step3;
use App\Form\PPF\Sunflower\PPF1Step4;
use App\Repository\InterventionsRepository;
use App\Repository\PPFInputRepository;
use App\Repository\PPFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PPFController
 * @package App\Controller
 * @Route("/admin/ppf/1")
 */
class PPF1Controller extends AbstractController
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
     * @Route("/step/1", name="ppf1_step_1", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step1( Request $request ): Response
    {
        $form = $this->createForm( PPF1Step1::class, null, ['exploitation' => $request->get('exploitation')] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Save to DB
            $ppf = new PPF();
            $ppf->setAddedDate( new \DateTime() );
            $ppf->setStatus( 1 );
            $ppf->setType( 1 );
            $ppf->setCulture( $data['culture'] );
            $ppf->setEffiencyPrev( $data['effiency_prev'] );
            $ppf->setQtyAzoteAddPrev( $data['qty_azote_add_prev'] );
            $ppf->setDateImplantationPlanned( $data['date_implantation_planned'] );

            $this->em->persist( $ppf );
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_2', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/step1.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/step/2", name="ppf1_step_2", methods={"GET", "POST"})
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
        $form = $this->createForm( PPF1Step2::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_3', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/step2.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf,
            'intervention' => $intervention
        ]);
    }

    /**
     * @Route("/step/3", name="ppf1_step_3", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step3( Request $request): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $form = $this->createForm( PPF1Step3::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_4', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/step3.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/step/4", name="ppf1_step_4", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function step4( Request $request): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $form = $this->createForm( PPF1Step4::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/step4.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/step/5", name="ppf1_step_5", methods={"GET", "POST"})
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
        $form = $this->createForm( PPF1Step4::class, $ppf );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ppf->setStatus( 2 );

            // Save to DB
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/step5.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf,
            'inputs' => $inputs
        ]);
    }

    /**
     * @Route("/step/add-input", name="ppf1_add_input", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function addInput( Request $request ): Response
    {
        // Get PPF
        $ppf = $this->ppfRepository->findOneBy( ['id' => $request->get('ppf')]);

        // Form
        $ppfInput = new PPFInput();
        $form = $this->createForm( PPF1AddInput::class, $ppfInput );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ppfInput->setPpf( $ppf );

            // Save to DB
            $this->em->persist( $ppfInput );
            $this->em->flush();

            return $this->redirectToRoute('ppf1_step_5', ['ppf' => $ppf->getId()]);
        }

        return $this->render('admin/PPF/1/add_input.html.twig', [
            'form' => $form->createView(),
            'ppf' => $ppf
        ]);
    }

    /**
     * @Route("/summary", name="ppf1_summary", methods={"GET", "POST"})
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

        return $this->render('admin/PPF/1/summary.html.twig', [
            'ppf' => $ppf,
            'intervention' => $intervention,
            'inputs' => $inputs
        ]);
    }

}

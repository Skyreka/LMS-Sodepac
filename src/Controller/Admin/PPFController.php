<?php

namespace App\Controller\Admin;

use App\Entity\PPF;
use App\Form\PPF\PPFStep1;
use App\Form\PPF\PPFStep2;
use App\Form\PPF\PPFStep3;
use App\Form\PPF\PPFUserSelect;
use App\Repository\InterventionsRepository;
use App\Repository\PPFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

<?php

namespace App\Controller\Admin;

use App\Entity\PPF;
use App\Form\PPF\PPFStep1;
use App\Form\PPF\PPFUserSelect;
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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
     * @return Response
     */
    public function step2( Request $request ): Response
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

        return $this->render('admin/PPF/step2.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller\Admin\PPF;

use App\Entity\PPF;
use App\Form\PPF\PPFUserSelect;
use App\Repository\PPFRepository;
use App\Repository\UsersRepository;
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

            return $this->redirectToRoute('ppf'. $form->get('types')->getData() .'_step_1', [
                'exploitation' => $data->getExploitation()->getId(),
                'types' => $form->get('types')->getData()
            ]);
        }

        return $this->render('admin/PPF/userSelect.html.twig', [
            'form' => $form->createView()
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
    public function selectData( Request $request, UsersRepository $ur): JsonResponse
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

    /**
     * @Route("/delete/{id}", name="ppf_delete", methods="DELETE", requirements={"id":"\d+"})
     * @param PPF $PPF
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteRecorded(PPF $PPF, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('deletePPF' . $PPF->getId(), $request->get('_token' ))) {
            $this->em->remove($PPF);
            $this->em->flush();

            $this->container->get('session')->remove('currentOrder');

            $this->addFlash('success', 'PPF supprimée avec succès');
        }
        return $this->redirect( $request->headers->get('referer') );
    }
}

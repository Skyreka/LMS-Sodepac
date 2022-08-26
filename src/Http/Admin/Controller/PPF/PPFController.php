<?php

namespace App\Http\Admin\Controller\PPF;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\PPF\Entity\PPF;
use App\Domain\PPF\Form\PPFUserSelect;
use App\Domain\PPF\Repository\PPFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ppf", name="ppf_")
 */
class PPFController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PPFRepository $ppfRepository
    )
    {
    }
    
    /**
     * @Route("/user-select", name="user_select", methods={"GET", "POST"})
     */
    public function userSelect(Request $request): Response
    {
        $form = $this->createForm(PPFUserSelect::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            // Display error if user don't have exploitation
            if($data->getExploitation() == NULL) {
                $this->addFlash('danger', 'Votre client n\'a aucune exploitation déclarée, veuillez modifier son compte pour pouvoir lui établir un catalogue');
                return $this->redirectToRoute('ppf_user_select');
            }
            
            return $this->redirectToRoute('admin_ppf' . $form->get('types')->getData() . '_step_1', [
                'exploitation' => $data->getExploitation()->getId(),
                'types' => $form->get('types')->getData()
            ]);
        }
        
        return $this->render('admin/PPF/userSelect.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/PPF/index.html.twig', [
            'ppfs' => $this->ppfRepository->findAll()
        ]);
    }
    
    /**
     * @Route("/new_data_ajax", name="select_data")
     */
    public function selectData(Request $request, UsersRepository $ur): JsonResponse
    {
        //Get information from ajax call
        $term  = $request->query->get('q');
        $limit = $request->query->get('page_limit');
        
        //Query of like call
        $users = $ur->createQueryBuilder('u')
            ->orWhere('u.lastname LIKE :lastname')
            ->orWhere('u.firstname LIKE :firstname')
            ->setParameter('lastname', '%' . $term . '%')
            ->setParameter('firstname', '%' . $term . '%')
            ->andWhere('u.pack = :pack')
            ->setParameter('pack', 'PACK_FULL')
            ->join(Exploitation::class, 'e', 'WITH', 'e.users = u.id')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        
        // Return Array of key = id && text = value
        $array = [];
        foreach($users as $user) {
            $array[] = array(
                'id' => $user->getExploitation()->getId(),
                'text' => $user->getIdentity()
            );
        }
        
        // Return JsonResponse of code 200
        return new JsonResponse($array, 200);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods="DELETE", requirements={"id":"\d+"})
     */
    public function deleteRecorded(PPF $PPF, Request $request): RedirectResponse
    {
        if($this->isCsrfTokenValid('deletePPF' . $PPF->getId(), $request->get('_token'))) {
            $this->em->remove($PPF);
            $this->em->flush();
            
            $this->container->get('session')->remove('currentOrder');
            
            $this->addFlash('success', 'PPF supprimée avec succès');
        }
        return $this->redirect($request->headers->get('referer'));
    }
}

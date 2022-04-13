<?php

namespace App\Http\Admin\Controller;

use App\Domain\Auth\Form\UserType;
use App\Domain\Auth\Users;
use App\Domain\Exploitation\Entity\Exploitation;
use DataTables\DataTablesInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/users/index.html.twig');
    }
    
    /**
     * @Route("/data", name="data", methods={"GET"})
     * @param Request $request
     * @param DataTablesInterface $datatables
     * @return JsonResponse
     */
    public function data(Request $request, DataTablesInterface $datatables): JsonResponse
    {
        try {
            $results = $datatables->handle($request, 'users');
            
            return $this->json($results);
        } catch(HttpException $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        }
    }
    
    private function getData(): array
    {
        $list  = [];
        $users = $this->em->getRepository(Users::class)->findAll();
        
        foreach($users as $user) {
            $technician = 'Aucun';
            if($user->getStatus() == 'ROLE_USER') {
                $technician = $user->getTechnician()->getIdentity();
            }
            $list[] = [
                $user->getIdentity(),
                $user->getEmail(),
                $user->getPhone(),
                $user->getCity(),
                $user->getPack(),
                $user->getCertificationPhyto(),
                $technician
            ];
        }
        return $list;
    }
    
    /**
     * @Route("/export", name="export", methods={"GET", "POST"})
     */
    public function export(): Response
    {
        $spreadsheet = new Spreadsheet();
        
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Liste des utilisateurs');
        
        $sheet->getCell('A1')->setValue('Informations');
        $sheet->getCell('B1')->setValue('Email');
        $sheet->getCell('C1')->setValue('Téléphone');
        $sheet->getCell('D1')->setValue('Ville');
        $sheet->getCell('E1')->setValue('Pack');
        $sheet->getCell('F1')->setValue('Certification');
        $sheet->getCell('G1')->setValue('Technicien');
        
        $sheet->fromArray($this->getData(), null, 'A2', true);
        
        $writer = new Xlsx($spreadsheet);
        
        $response = new StreamedResponse(
            function() use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="Utilisateurs.xls"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
    
    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        AsyncMethodService $asyncMethodService
    ): Response
    {
        $user = new Users();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, '0000'));
            $user->setStatus('ROLE_USER');
            $user->setIsActive(1);
            
            $this->em->persist($user);
            
            //Create exploitation
            $exploitation = new Exploitation();
            $exploitation
                ->setSize(300)
                ->setUsers($user);
            
            $this->em->persist($exploitation);
            $this->em->flush();
            
            //Send Email to user
            $asyncMethodService->async(EmailNotifier::class, 'notify', ['userId' => $user->getId(),
                'params' => [
                    'subject' => 'Votre compte ' . $this->getParameter('APP_NAME') . ' est maintenant disponible.',
                    'text1' => 'Votre compte ' . $this->getParameter('APP_NAME') . ' est maintenant disponible.'
                ]
            ]);
            
            $this->addFlash('success', 'Utilisateur crée avec succès');
            return $this->redirectToRoute('admin_index');
        }
        
        return $this->render('admin/users/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/delete/{id}", name="delete", methods="DELETE", requirements={"id":"\d+"})
     */
    public function delete(Users $user, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }
        return $this->redirectToRoute('admin_index');
    }
}

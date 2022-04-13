<?php

namespace App\Http\Admin\Controller;

use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Auth\Users;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/technicians", name="technicians_")
 */
class TechnicianController extends AbstractController
{
    
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UsersRepository $ur): Response
    {
        return $this->render('admin/technician/index.html.twig', [
            'technicians' => $ur->findAllByRole('ROLE_TECHNICIAN')
        ]);
    }
    
    /**
     * @Route("/users/{id}", name="users", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function users(Users $technician, UsersRepository $ur): Response
    {
        return $this->render('admin/technician/users.html.twig', [
            'users' => $ur->findBy(['technician' => $technician->getId()]),
            'technician' => $technician
        ]);
    }
    
    /**
     * @Route("/export/{id}", name="export", methods={"GET", "POST"})
     */
    public function export(Users $technician): Response
    {
        $spreadsheet = new Spreadsheet();
        
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Utilisateurs Tech ' . $technician->getFirstname());
        
        $sheet->getCell('A1')->setValue('Informations');
        $sheet->getCell('B1')->setValue('Entreprise');
        $sheet->getCell('C1')->setValue('Adresse');
        $sheet->getCell('D1')->setValue('Email');
        $sheet->getCell('E1')->setValue('Téléphone');
        $sheet->getCell('F1')->setValue('Ville');
        $sheet->getCell('G1')->setValue('Pack');
        
        $sheet->fromArray($this->getData($technician), null, 'A2', true);
        
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
    
    private function getData(Users $technician): array
    {
        $list  = [];
        $users = $this->em->getRepository(Users::class)->findByTechnician($technician);
        
        foreach($users as $user) {
            switch($user->getPack()) {
                case 'PACK_FULL':
                    $pack = 'PACK FULL';
                    break;
                case 'PACK_LIGHT':
                    $pack = 'PACK LIGHT';
                    break;
                case 'PACK_DEMO':
                    $pack = 'PACK DEMO';
                    break;
                default:
                    $pack = 'INACTIF';
                    break;
            }
            $list[] = [
                $user->getIdentity(),
                $user->getCompany(),
                $user->getAddress() . ' ' . $user->getPostalCode() . ' ' . $user->getCity(),
                $user->getEmail(),
                $user->getPhone(),
                $user->getCity(),
                $pack
            ];
        }
        return $list;
    }
}

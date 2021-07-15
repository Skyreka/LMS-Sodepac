<?php
namespace App\Controller\Admin;

use App\Entity\Exploitation;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
use App\Form\UserType;
use App\Repository\RecommendationProductsRepository;
use App\Repository\UsersRepository;
use DataTables\DataTablesInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TechnicianController
 * @package App\Controller
 * @Route("/admin/technicians")
 */
class TechnicianController extends AbstractController {

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="admin_technicians_index", methods={"GET"})
     */
    public function index( UsersRepository $ur ): Response
    {
        return $this->render('admin/technician/index.html.twig', [
            'technicians' => $ur->findAllByRole( 'ROLE_TECHNICIAN' )
        ]);
    }

    /**
     * @Route("/users/{id}", name="admin_technicians_users", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function users( Users $technician, UsersRepository $ur ): Response
    {
        return $this->render('admin/technician/users.html.twig', [
            'users' => $ur->findBy( ['technician' => $technician->getId()] ),
            'technician' => $technician
        ]);
    }
}

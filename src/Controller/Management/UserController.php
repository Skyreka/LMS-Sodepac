<?php

namespace App\Controller\Management;

use App\Entity\Cultures;
use App\Entity\Exploitation;
use App\Entity\Ilots;
use App\Entity\Users;
use App\Form\ExploitationType;
use App\Form\PasswordType;
use App\Form\TechnicianCustomersType;
use App\Form\UserType;
use App\Repository\AnalyseRepository;
use App\Repository\CulturesRepository;
use App\Repository\IlotsRepository;
use App\Repository\InterventionsRepository;
use App\Repository\IrrigationRepository;
use App\Repository\StocksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * Management Controller nly for view information of user let's ilots / culture .. for edit is only switch by role of user view profil
 * @package App\Controller\Management
 * @Route("/management/user")
 */
class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * View information of user for tech and admin
     * @Route("/{id}", name="management_user_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Users $user
     * @param StocksRepository $sr
     * @param IlotsRepository $ir
     * @param IrrigationRepository $irrigationRepo
     * @param AnalyseRepository $analyseRepo
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function index(
        Users $user,
        StocksRepository $sr,
        IlotsRepository $ir,
        IrrigationRepository $irrigationRepo,
        AnalyseRepository $analyseRepo,
        Request $request,
        UserPasswordEncoderInterface $encoder
        ): Response
    {
        $tab = $request->query->get('tab');
        if ( empty( $tab )) {
            $activeTab = 'default';
        } else {
            $activeTab = $tab;
        }

        $isTechnician = false; $isAdmin = false;
        if ($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $isTechnician = true;
        } elseif($this->isGranted( 'ROLE_ADMIN')) {
            $isAdmin = true;
        }

        // Security for technican can't view customer of other technican
        if ( $isTechnician AND $user->getTechnician() != $this->getUser() ) {
            throw $this->createNotFoundException('Cette utilisateur ne vous appartient pas.');
        }

        // Get informations of user
        $exploitation = $user->getExploitation();
        $usedProducts = $sr->findByExploitation( $exploitation, true );
        $ilots = $ir->findBy( ['exploitation' => $exploitation], null );
        $irrigations = $irrigationRepo->findByExploitation( $exploitation );
        $analyses = $analyseRepo->findByExploitation( $exploitation );

        // Edit Password
        $formPassword = $this->createForm( PasswordType::class, $user);
        $formPassword->handleRequest( $request );
        if ( $formPassword->isSubmitted() && $formPassword->isValid() ) {
            $user->setPassword( $encoder->encodePassword($user, $formPassword['password']->getData()));
            //To display alert on user
            $user->setReset(1);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId(), 'tab' => 'password']);
        } elseif ( $formPassword->isSubmitted() && $formPassword->isValid() == false ) {
            $this->addFlash('danger', 'Une erreur est survenue. Le mot de passe doit faire au moins 6 caractères et les 2 identiques.');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId(), 'tab' => 'password']);
        }

        // Edit Information
        if ($isTechnician) {
            $formInformation = $this->createForm( TechnicianCustomersType::class, $user);
        } elseif($isAdmin) {
            $formInformation = $this->createForm( UserType::class, $user);
        }
        $formInformation->handleRequest( $request );
        if ( $formInformation->isSubmitted() && $formInformation->isValid() ) {
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId()]);
        }

        // Edit Exploitation
        if ($user->getExploitation() == NULL) {
            $exploitation = new Exploitation();
            $exploitation->setUsers( $user );
        } else {
            $exploitation = $user->getExploitation();
        }
        $formExploitation = $this->createForm(ExploitationType::class, $exploitation);
        $formExploitation->handleRequest( $request );
        if ($formExploitation->isSubmitted() && $formExploitation->isValid()) {
            if ($user->getExploitation() == NULL) {
                $this->em->persist( $exploitation );
            }
            $this->em->flush();
            $this->addFlash('success', 'Exploitation modifiée avec succès');
            return $this->redirectToRoute( 'management_user_show', ['id' => $user->getId()] );
        }

        return $this->render('management/user/index.html.twig', [
            'user' => $user,

            'usedProducts' => $usedProducts,
            'ilots' => $ilots,
            'irrigations' => $irrigations,
            'analyses' => $analyses,

            'form_password' => $formPassword->createView(),
            'form_information' => $formInformation->createView(),
            'form_exploitation' => $formExploitation->createView(),

            'activeTab' => $activeTab
        ]);
    }

    /**
     * @Route("/{user}/ilot/{ilot}", name="management_user_ilot_show", methods={"GET"}, requirements={"id":"\d+"})
     * @param Users $user
     * @param Ilots $ilot
     * @param CulturesRepository $cr
     * @return Response
     */
    public function showIlots(Users $user, Ilots $ilot, CulturesRepository $cr): Response
    {
        // Security for technican can't view customer of other technican
        if ( $this->getUser()->getStatus() == 'ROLE_TECHNICIAN' AND $user->getTechnician() != $this->getUser() ) {
            throw $this->createNotFoundException('Cette utilisateur ne vous appartient pas.');
        }

        $cultures = $cr->findBy( ['ilot' => $ilot] );

        return $this->render('management/user/ilot.html.twig', [
            'user' => $user,
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }

    /**
     * @Route("/culture/{id}", name="management_user_culture_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     * @param Cultures $culture
     * @param InterventionsRepository $interventionsRepository
     * @return Response
     */
    public function showCulture(Cultures $culture, InterventionsRepository $interventionsRepository): Response
    {
        return $this->render('management/user/culture.html.twig', [
            'culture' => $culture,
            'interventions' => $interventionsRepository
        ]);
    }
}

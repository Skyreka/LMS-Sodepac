<?php

namespace App\Http\Controller\Management;

use App\Domain\Auth\Form\UserType;
use App\Domain\Auth\Users;
use App\Domain\Culture\Entity\Cultures;
use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Exploitation\Entity\Exploitation;
use App\Domain\Exploitation\Form\ExploitationType;
use App\Domain\Ilot\Entity\Ilots;
use App\Domain\Ilot\Repository\IlotsRepository;
use App\Domain\Intervention\Repository\InterventionsRepository;
use App\Domain\Stock\Repository\StocksRepository;
use App\Http\Form\PasswordType;
use App\Http\Form\TechnicianCustomersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * Management Controller nly for view information of user let's ilots / culture .. for edit is only switch by role of user view profil
 * @package App\Controller\Management
 * @Route("/management/user")
 */
class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    /**
     * @Route("/{id}", name="management_user_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function index(
        Users $user,
        StocksRepository $sr,
        IlotsRepository $ir,
        Request $request,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $tab = $request->query->get('tab');
        if(empty($tab)) {
            $activeTab = 'default';
        } else {
            $activeTab = $tab;
        }
        
        $isTechnician = false;
        $isAdmin      = false;
        if($this->getUser()->getStatus() == 'ROLE_TECHNICIAN') {
            $isTechnician = true;
        } elseif($this->isGranted('ROLE_ADMIN')) {
            $isAdmin = true;
        }
        
        // Security for technican can't view customer of other technican
        if($isTechnician and $user->getTechnician() != $this->getUser()) {
            throw $this->createNotFoundException('Cette utilisateur ne vous appartient pas.');
        }
        
        // Get informations of user
        $exploitation = $user->getExploitation();
        $usedProducts = $sr->findByExploitation($exploitation, true);
        $ilots        = $ir->findBy(['exploitation' => $exploitation], null);
        
        // Edit Password
        $formPassword = $this->createForm(PasswordType::class, $user);
        $formPassword->handleRequest($request);
        if($formPassword->isSubmitted() && $formPassword->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $formPassword['password']->getData()));
            //To display alert on user
            $user->setReset(1);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId(), 'tab' => 'password']);
        } elseif($formPassword->isSubmitted() && $formPassword->isValid() == false) {
            $this->addFlash('danger', 'Une erreur est survenue. Le mot de passe doit faire au moins 6 caractères et les 2 identiques.');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId(), 'tab' => 'password']);
        }
        
        // Edit Information
        if($isTechnician) {
            $formInformation = $this->createForm(TechnicianCustomersType::class, $user);
        } elseif($isAdmin) {
            $formInformation = $this->createForm(UserType::class, $user);
        }
        $formInformation->handleRequest($request);
        if($formInformation->isSubmitted() && $formInformation->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId()]);
        }
        
        // Edit Exploitation
        if($user->getExploitation() == NULL) {
            $exploitation = new Exploitation();
            $exploitation->setUsers($user);
        } else {
            $exploitation = $user->getExploitation();
        }
        $formExploitation = $this->createForm(ExploitationType::class, $exploitation);
        $formExploitation->handleRequest($request);
        if($formExploitation->isSubmitted() && $formExploitation->isValid()) {
            if($user->getExploitation() == NULL) {
                $this->em->persist($exploitation);
            }
            $this->em->flush();
            $this->addFlash('success', 'Exploitation modifiée avec succès');
            return $this->redirectToRoute('management_user_show', ['id' => $user->getId()]);
        }
        
        return $this->render('management/user/index.html.twig', [
            'user' => $user,
            
            'usedProducts' => $usedProducts,
            'ilots' => $ilots,
            
            'form_password' => $formPassword->createView(),
            'form_information' => $formInformation->createView(),
            'form_exploitation' => $formExploitation->createView(),
            
            'activeTab' => $activeTab
        ]);
    }
    
    /**
     * @Route("/{user}/ilot/{ilot}", name="management_user_ilot_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function showIlots(Users $user, Ilots $ilot, CulturesRepository $cr): Response
    {
        // Security for technican can't view customer of other technican
        if($this->getUser()->getStatus() == 'ROLE_TECHNICIAN' and $user->getTechnician() != $this->getUser()) {
            throw $this->createNotFoundException('Cette utilisateur ne vous appartient pas.');
        }
        
        $cultures = $cr->findBy(['ilot' => $ilot]);
        
        return $this->render('management/user/ilot.html.twig', [
            'user' => $user,
            'ilot' => $ilot,
            'cultures' => $cultures
        ]);
    }
    
    /**
     * @Route("/culture/{id}", name="management_user_culture_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function showCulture(Cultures $culture, InterventionsRepository $interventionsRepository): Response
    {
        return $this->render('management/user/culture.html.twig', [
            'culture' => $culture,
            'interventions' => $interventionsRepository
        ]);
    }
}

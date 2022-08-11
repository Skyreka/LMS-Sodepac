<?php

namespace App\Http\Controller\Interventions;

use App\Domain\Culture\Repository\CulturesRepository;
use App\Domain\Index\Entity\IndexCultures;
use App\Domain\Index\Repository\IndexCulturesRepository;
use App\Domain\Intervention\Form\MultipleInterventionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MultipleInterventionController
 * @package App\Controller\Interventions
 * @Route("/interventions-multiple")
 */
class MultipleInterventionController extends AbstractController
{
    /**
     * @Route("/select", name="intervention_multiple_index")
     */
    public function index(
        Request $request,
        IndexCulturesRepository $icr,
        CulturesRepository $cr
    ): Response
    {
        //-- Form
        $indexCultures = new IndexCultures();
        $form = $this->createForm(MultipleInterventionType::class, $indexCultures, ['user' => $this->getUser()]);
        $form->handleRequest($request);
    
        $foundCultures = $cr->findByIndexCultureInProgress($request->get('culture'), $this->getUser()->getExploitation());
    
    
        $culturesSelected = $request->request->get('cultures_selected');
        if($culturesSelected) {
            $cultureFounded = [];
            // Put array to session
            foreach( $culturesSelected as $id ) {
                $culture = $cr->findOneBy(['id' => $id]);
                $cultureFounded[] = $culture;
            }
            $this->container->get('session')->set('listCulture', $cultureFounded);
            
            return $this->redirectToRoute('cultures_show', ['id' => $culturesSelected[0]]);
        }
        
        return $this->render('interventions/multiple/index.html.twig', [
            'form' => $form->createView(),
            'active_cultures' => $icr->findActiveCultureByExploitation($this->getUser()->getExploitation()),
            'found_cultures' => $foundCultures
        ]);
    }
}

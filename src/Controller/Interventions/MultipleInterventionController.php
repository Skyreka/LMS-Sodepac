<?php
namespace App\Controller\Interventions;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use App\Form\MultipleInterventionType;
use App\Repository\CulturesRepository;
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
     * @param Request $request
     * @param CulturesRepository $cr
     * @return Response
     */
    public function index( Request $request, CulturesRepository $cr ): Response
    {
        //-- Form
        $indexCultures = new IndexCultures();
        $form = $this->createForm( MultipleInterventionType::class, $indexCultures, ['user' => $this->getUser()]);
        $form->handleRequest( $request );

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->all();
            $listCultures = $data['cultures']->getData();

            if ($listCultures->isEmpty()) {
                $this->addFlash('danger', 'Veuillez sélectionner au moins une culture.');
                return $this->redirectToRoute('intervention_multiple_index');
            }

            // Put array to session
            $this->container->get('session')->set('listCulture', $listCultures);

            return $this->redirectToRoute('cultures_show', ['id' => $listCultures[0]->getId()]);
        }

        return $this->render('interventions/multiple/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Entity\IndexCultures;
use App\Form\MultipleInterventionType;
use App\Repository\CulturesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MultipleInterventionController extends AbstractController
{
    /**
     * @Route("multiple/select", name="multiple.intervention.index")
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
            $listIlots = $data['ilots']->getData();
            $cultureFounded = [];

            //Find All Culture selected by group of ilot
            foreach ($listIlots as $ilot) {
                array_push( $cultureFounded, $cr->findByIlotCultureInProgress( $ilot ));
            }

            $cultureFounded = call_user_func_array('array_merge', $cultureFounded);

            //--Send Info to select Multiple Intervention
            $this->container->get('session')->set('listCulture', $cultureFounded);

            //-- Get first value of array to display an correct page
            $cultureFirst = array_values($cultureFounded)[0];
            return $this->redirectToRoute('cultures.show', ['id' => $cultureFirst->getId()]);
        }

        return $this->render('multipleIntervention/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("multiple/interventions", name="multiple.interventions")
     * @param Request $request
     * @return Response
     */
    public function intervention( Request $request ): Response
    {
        dump( $this->container->get('session')->get('listCulture') );
        return $this->render('multipleIntervention/interventions.html.twig');
    }
}
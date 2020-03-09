<?php
namespace App\Controller;

use App\Entity\Cultures;
use App\Form\CulturesNewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CulturesController extends AbstractController
{
    /**
     * @Route("/cultures/new/{id_ilot}", name="cultures.new")
     * @return Response
     */
    public function new(): Response
    {
        $culture = new Cultures();
        $form = $this->createForm( CulturesNewType::class, $culture);
        return $this->render('cultures/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
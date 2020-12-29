<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HelpController
 * @Route("/help")
 */
class HelpController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="help_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('help/index.html.twig');
    }
}

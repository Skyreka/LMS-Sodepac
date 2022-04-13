<?php

namespace App\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelpController
 * @Route("help")
 */
class HelpController extends AbstractController
{
    /**
     * @return Response
     * @Route("", name="help_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('help/index.html.twig');
    }
}

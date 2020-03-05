<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ErrorsController extends AbstractController
{
    /**
     * @Route("401", name="error401")
     */
    public function error401() {
        return $this->render('errors/401.html.twig');
    }
}
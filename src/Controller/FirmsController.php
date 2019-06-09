<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirmsController extends AbstractController
{
    /**
     * @Route("/firms", name="firms_main")
     */
    public function main(): Response
    {
        return $this->render('pages/firms/index.html.twig');
    }
}

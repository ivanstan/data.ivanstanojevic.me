<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AirportController extends AbstractController
{
    /**
     * @Route("/airport/{icao}", name="airport_show")
     */
    public function show(string $icao): Response
    {
        return $this->render('airport/show.html.twig', ['icao' => $icao]);
    }
}

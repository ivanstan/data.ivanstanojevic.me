<?php

namespace App\Controller;

use App\Entity\Airport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AirportController extends AbstractController
{
    /**
     * @Route("/airport/{icao}", name="airport_show")
     */
    public function view(string $icao): Response
    {
        $airport = $this->getDoctrine()->getRepository(Airport::class)->findOneBy(['icao' => $icao]);

        if ($airport === null) {
            throw new NotFoundHttpException(sprintf('Unable to find airport with ICAO code %s', $icao));
        }

        return $this->render('pages/airport/view.html.twig', ['airport' => $airport]);
    }
}

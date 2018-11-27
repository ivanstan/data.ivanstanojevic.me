<?php

namespace App\Controller;

use App\Repository\AirportRepository;
use App\Repository\MetarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class DataController extends AbstractController
{
    /** @var string */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @Route("/data", name="app_data_canonical")
     * @Route("/", name="app_data_index", host="data.ivanstanojevic.me")
     */
    public function index(MetarRepository $metarRepository, AirportRepository $airportRepository): Response
    {
        $airports = $airportRepository->getCollection($metarRepository->getAirportsWithMetarData());

        return $this->render('index.html.twig', [
            'airports' => $airports,
        ]);
    }
}

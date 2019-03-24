<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /** @var string */
    private $apiKey;

    public function __construct($mapsApiKey)
    {
        $this->apiKey = $mapsApiKey;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('pages/presentation/index.html.twig');
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('pages/dashboard/index.html.twig', ['api_key' => $this->apiKey]);
    }
}

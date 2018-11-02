<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /** @var string */
    private $dir;

    public function __construct($projectDir)
    {
        $this->dir = $projectDir.'/config/custom/';
    }

    /**
     * @Route("/api/{name}/docs", name="app_api_docs")
     */
    public function getDocs(string $name): Response
    {
        return $this->render('api/docs.html.twig', ['name' => $name]);
    }

    /**
     * @Route("/api/{name}/json", name="app_api_docs_json")
     */
    public function getJson(string $name): Response
    {
        $file = json_decode(file_get_contents($this->dir.'general.json'), true);
        $file['paths'] = json_decode(file_get_contents($this->dir.'/'.$name.'.json'), true);

        return new JsonResponse($file);
    }
}

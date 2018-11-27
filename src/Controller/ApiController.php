<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

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
    public function html(string $name): Response
    {
        $catalog = Yaml::parseFile($this->dir.'/catalog.yaml');
        $page = $catalog[$name] ?? [];

        return $this->render(
            'pages/api/docs.html.twig',
            [
                'name' => $name,
                'description' => $page['description'] ?? null,
                'title' => $page['name'] ?? null,
            ]
        );
    }

    /**
     * @Route("/api/{name}/json", name="app_api_docs_json")
     */
    public function json(string $name): Response
    {
        $file = json_decode(file_get_contents($this->dir.'general.json'), true);
        $file['paths'] = json_decode(file_get_contents($this->dir.'/'.$name.'.json'), true);

        return new JsonResponse($file);
    }
}

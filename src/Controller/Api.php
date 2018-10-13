<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Api extends Controller
{
    private const OPEN_API_ANNOTATION_PATHS = ['src/Controller'];

    /**
     * @Route("/", name="api_doc")
     */
    public function htmlDoc()
    {
        return $this->render('doc.html.twig');
    }

    /**
     * @Route("/openapi", name="api_doc_json")
     */
    public function openApiJson()
    {
        $roodDir = $this->getParameter('kernel.project_dir');
        $cacheDir = $this->getParameter('kernel.cache_dir');
        $debug = $this->getParameter('kernel.debug');

        $cache = new ConfigCache("$cacheDir/open-api/open-api.json", $debug);
        if (!$cache->isFresh()) {
            $dirs = array_map(
                function ($dir) use ($roodDir) {
                    return "$roodDir/$dir";
                },
                self::OPEN_API_ANNOTATION_PATHS
            );

            $openApi = \OpenApi\scan($dirs);

            $resources = [];
            foreach ($dirs as $dir) {
                $resources[] = new DirectoryResource($dir);
            }

            $spec = $openApi->toJson();
            $cache->write($spec, $resources);
        }

        return JsonResponse::fromJsonString(file_get_contents($cache->getPath()));
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Api extends Controller
{
    /**
     * @Route("/", name="api_doc")
     */
    public function docsHtml()
    {
        return $this->render('doc.html.twig');
    }

    /**
     * @Route("/openapi", name="api_doc_json")
     */
    public function docsJson()
    {
        $file = file_get_contents(__DIR__.'/Api/openapi.json');

        return JsonResponse::fromJsonString($file);
    }
}

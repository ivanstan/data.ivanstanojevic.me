<?php

namespace App\Controller;

use App\Service\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /** @var string */
    private $dir;

    /** @var Json */
    private $json;

    public function __construct(string $projectDir, Json $json)
    {
        $this->dir = $projectDir.'/config/custom';
        $this->json = $json;
    }

    /**
     * @Route("/blog", name="app_blog_canonical")
     * @Route("/", name="app_blog_index", host="blog.ivanstanojevic.me")
     */
    public function index(Request $request): Response
    {
        $list = $this->json->decode($this->dir.'/blog.json');

        $articles = [];
        foreach ($list as $item) {
            $locale = $item['locale'] ?? null;

            if ($locale && $locale === $request->getLocale()) {
                $articles[] = $item;
            }
        }

        return $this->render('pages/blog/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/blog/{slug}", name="app_article_canonical")
     * @Route("/{slug}", name="app_article", host="blog.ivanstanojevic.me")
     */
    public function read(Request $request, string $slug): Response
    {
        $list = $this->json->decode($this->dir.'/blog.json');
        $locale = $request->getLocale();

        foreach ($list as $key => $item) {
            if ($slug === ($item['slug'] ?? null) && $locale === ($item['locale'] ?? null)) {
                return $this->render(
                    "pages/blog/posts/{$key}.html.twig",
                    ['article' => $item]
                );
            }
        }

        throw new NotFoundHttpException('Article not found.');
    }
}

<?php

namespace App\Controller;

use App\Service\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class BlogController extends AbstractController
{
    /** @var string */
    private $dir;

    /** @var Json */
    private $json;

    /** @var RouterInterface */
    private $router;

    public function __construct(string $projectDir, Json $json, RouterInterface $router)
    {
        $this->dir = $projectDir.'/config/custom/blog';
        $this->json = $json;
        $this->router = $router;
    }

    /**
     * @Route("/blog", name="app_blog_canonical")
     * @Route("/", name="app_blog_index", host="blog.ivanstanojevic.me")
     */
    public function index(Request $request): Response
    {
        $list = $this->json->decode($this->dir.'/list.json');

        $articles = [];
        foreach ($list as $item) {
            $articles[] = $this->localize($item, $request->getLocale());
        }

        return $this->render('blog/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/blog/{slug}", name="app_article_canonical")
     * @Route("/{slug}", name="app_article", host="blog.ivanstanojevic.me")
     */
    public function read(Request $request, string $slug): Response
    {
        $list = $this->json->decode($this->dir.'/list.json');
        $locale = $request->getLocale();

        foreach ($list as $key => $item) {
            if ($slug === ($item['slug_en'] ?? null) || $slug === ($item['slug_rs'] ?? null)) {
                return $this->render(
                    "blog/posts/{$key}_{$locale}.html.twig",
                    ['article' => $this->localize($item, $request->getLocale())]
                );
            }
        }

        throw new NotFoundHttpException('Article not found.');
    }

    private function localize(array $item, string $locale): array
    {
        return [
            'image' => $item['image'],
            'title' => $item['title_'.$locale],
            'teaser' => $item['teaser_'.$locale],
            'url' => $this->router->generate('app_article_canonical', ['slug' => $item['slug_'.$locale]]),
        ];
    }
}

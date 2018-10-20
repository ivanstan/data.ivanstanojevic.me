<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController extends Controller
{
    protected const SORT_ASC = 'asc';
    protected const SORT_DESC = 'desc';

    protected const SORT_PARAM = 'sort';
    protected const SORT_DIR_PARAM = 'sort-dir';

    protected static $sortDirection = [self::SORT_ASC, self::SORT_DESC];

    protected const PAGE_SIZE_PARAM = 'page-size';
    protected const PAGE_PARAM = 'page';
    protected const PAGE_SIZE = 50;
    protected const SEARCH_PARAM = 'search';

    /** @var SerializerInterface */
    protected $serializer;

    /** @var RouterInterface */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->serializer = new Serializer(
            [new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer()],
            [new \Symfony\Component\Serializer\Encoder\JsonEncoder()]
        );
        $this->router = $router;
    }

    public function getSort(Request $request, string $default, array $available): string
    {
        if ($request->get(self::SORT_PARAM) && \in_array($request->get(self::SORT_PARAM), $available, true)) {
            $default = strtolower($request->get('sort'));
        }

        return $default;
    }

    public function getSortDirection(Request $request, string $default): string
    {
        if ($request->get(self::SORT_DIR_PARAM) && \in_array(
                $request->get(self::SORT_DIR_PARAM),
                self::$sortDirection,
                true
            )) {
            $default = strtolower($request->get(self::SORT_DIR_PARAM));
        }

        return $default;
    }

    public function getPageSize(Request $request, int $default = self::PAGE_SIZE): int
    {
        if ($request->get(self::PAGE_SIZE_PARAM) && \is_numeric($request->get(self::PAGE_SIZE_PARAM))) {
            $default = $request->get(self::PAGE_SIZE_PARAM);
        }

        return $default;
    }

    public function getPage(Request $request): int
    {
        return $request->get(self::PAGE_PARAM, 1);
    }

    public function getPageOffset(int $page, int $pageSize): int
    {
        $offset = 0;
        if ($page > 1) {
            $offset = ($page - 1) * $pageSize;
        }

        return $offset;
    }

    public function getPagination(Request $request, int $total, int $pageSize): array
    {
        $params = $request->query->all();

        $page = $this->getPage($request);
        $pages = ceil($total / $pageSize);

        $nextPage = $page;
        if ($page < $pages) {
            $nextPage = $page + 1;
        }

        $previousPage = $page;
        if ($page > 1) {
            $previousPage = $page - 1;
        }

        $result = [
            '@id' => $this->router->generate(
                $request->attributes->get('_route'),
                array_merge($params, ['page' => $page]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            '@type' => 'PartialCollectionView',
            'first' => $this->router->generate(
                $request->attributes->get('_route'),
                array_merge($params, ['page' => 1]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'previous' => $this->router->generate(
                $request->attributes->get('_route'),
                array_merge($params, ['page' => $previousPage]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'next' => $this->router->generate(
                $request->attributes->get('_route'),
                array_merge($params, ['page' => $nextPage]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'last' => $this->router->generate(
                $request->attributes->get('_route'),
                array_merge($params, ['page' => $pages]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];

        if ($page === 1) {
            unset($result['previous']);
        }

        if ($page === $nextPage) {
            unset($result['next']);
        }

        return $result;
    }

    public function response($response): Response
    {
        return new Response(
            $this->serializer->serialize($response, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }

    public function getCurrentUrl(Request $request): string
    {
        return $request->getSchemeAndHttpHost().$request->getRequestUri();
    }
}

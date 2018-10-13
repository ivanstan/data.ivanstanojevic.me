<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Open Data",
 *         version="1.0.0"
 *     ),
 *     @OA\Server(url="/api/v1/tle")
 * )
 *
 * @OA\Response(
 *     response=404,
 *     description="Record not found",
 *     @OA\JsonContent(
 *          type="object",
 *          @OA\Property(
 *              property="response",
 *              type="object",
 *              @OA\Property(property="code", type="integer"),
 *              @OA\Property(property="message", type="string")
 *          ),
 *          example={"response": {"code": 404, "message": "Record not found"}}
 *     )
 * )
 * @OA\Response(
 *     response=500,
 *     description="Server error",
 *     @OA\JsonContent(
 *          type="object",
 *          @OA\Property(
 *              property="response",
 *              type="object",
 *              @OA\Property(property="code", type="integer"),
 *              @OA\Property(property="message", type="string")
 *          ),
 *          example={"response": {"code": 500, "message": "Server error"}}
 *     )
 * )
 */
abstract class AbstractApiController extends Controller
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    public const SORT_PARAM = 'sort';
    public const SORT_DIR_PARAM = 'sort-dir';

    public static $sortDirection = [self::SORT_ASC, self::SORT_DESC];

    public const PAGE_SIZE_PARAM = 'page-size';
    public const PAGE_PARAM = 'page';
    public const DEFAULT_PAGE_SIZE = 10;

    /** @var SerializerInterface */
    private $serializer;

    /** @var RouterInterface */
    private $router;

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

    public function getPageSize(Request $request, int $default = self::DEFAULT_PAGE_SIZE): int
    {
        if ($request->get(self::PAGE_SIZE_PARAM) && \is_numeric($request->get(self::PAGE_SIZE_PARAM))) {
            $default = $request->get(self::PAGE_SIZE_PARAM);
        }

        return $default;
    }

    public function getPage(Request $request) {
        return $request->get(self::PAGE_PARAM);
    }

    public function getPageOffset(int $page, int $pageSize) {
        $offset = 0;
        if ($page > 1) {
            $offset = ($page - 1) * $pageSize;
        }

        return $offset;
    }

    public function getPagination(Request $request, string $route, int $total, int $pageSize) {
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

        return [
            '@id' => $this->router->generate(
                $route,
                array_merge($params, ['page' => $page]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            '@type' => 'PartialCollectionView',
            'first' => $this->router->generate(
                $route,
                array_merge($params, ['page' => 1]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'previous' => $this->router->generate(
                $route,
                array_merge($params, ['page' => $previousPage]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'next' => $this->router->generate(
                $route,
                array_merge($params, ['page' => $nextPage]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'last' => $this->router->generate(
                $route,
                array_merge($params, ['page' => $pages]),
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];
    }

    public function response($response): Response
    {
        return new Response(
            $this->serializer->serialize($response, 'json'),
            Response::HTTP_OK,
            ['Content-type' => 'application/json']
        );
    }
}

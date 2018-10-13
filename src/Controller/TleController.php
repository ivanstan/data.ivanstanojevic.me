<?php

namespace App\Controller;

use App\Converter\TleModelConverter;
use App\Entity\Tle;
use App\Repository\TleRepository;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/api/v1/tle")
 * @OA\Schema(
 *     schema="TLE",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="line1", type="string"),
 *     @OA\Property(property="line2", type="string"),
 *     example={
 *          "id": 43630,
 *          "name": "HTV-7 (KOUNOTORI 7)",
 *          "line1": "1 43630U 18073A   18285.64337553  .00002296  00000-0  42374-4 0  9998",
 *          "line2": "2 43630  51.6412 153.3949 0003517 275.8456 137.9883 15.53813372136786"
 *     },
 * )
 */
class TleController extends AbstractApiController
{
    private const SEARCH_PARAM = 'search';

    /** @var TleModelConverter */
    private $converter;

    public function __construct(TleModelConverter $converter, RouterInterface $router)
    {
        parent::__construct($router);
        $this->converter = $converter;
    }

    /**
     * @Route("/{id}", name="tle_record", requirements={"id"="\d+"})
     *
     * @OA\Get(
     *     path="/{id}",
     *     summary="Record",
     *     operationId="tle-record",
     *     tags={"Two line element"},
     *
     *     @OA\Parameter(
     *         @OA\Schema(type="integer"),
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Record found",
     *         @OA\JsonContent(ref="#/components/schemas/TLE")
     *     ),
     *     @OA\Response(response=404, ref="#/components/responses/404"),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function record(int $id, TleRepository $repository): Response
    {
        /** @var Tle $tle */
        $tle = $repository->findOneBy(['satelliteId' => $id]);

        if ($tle === null) {
            throw new NotFoundHttpException(\sprintf('Unable to find record with id %s', $id));
        }

        $model = $this->converter->convert($tle);

        return $this->response($model);
    }

    /**
     * @Route("/", name="tle_collection")
     *
     * @OA\Get(
     *     path="/",
     *     summary="Collection",
     *     operationId="tle-collection",
     *     tags={"Two line element"},
     *
     *     @OA\Parameter(
     *         @OA\Schema(type="string"),
     *         name="search",
     *         in="query",
     *         description="Search string",
     *     ),
     *
     *     @OA\Parameter(
     *          @OA\Schema(type="string", enum={"id", "name"}, default="name"),
     *          name="sort",
     *          in="query",
     *          description="Sort by"
     *     ),
     *
     *     @OA\Parameter(
     *          @OA\Schema(type="string", enum={"asc", "desc"}, default="asc"),
     *          name="sort-dir",
     *          in="query",
     *          description="Sort direction"
     *     ),
     *
     *     @OA\Parameter(
     *          @OA\Schema(type="integer", default="1"),
     *          name="page",
     *          in="query",
     *          description="Current page"
     *     ),
     *
     *     @OA\Parameter(
     *          @OA\Schema(type="integer", default="50"),
     *          name="page-size",
     *          in="query",
     *          description="Page size"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tle collection",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="member",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TLE")
     *             ),
     *				example={
     *                  "@context": "http://www.w3.org/ns/hydra/context.jsonld",
     *                  "@id": "http://localhost/projects/tle/api/v1/tle/",
     *                  "@type": "Collection",
     *                  "totalItems": 92,
     *                  "member": {
     *                      {
     *                          "id": 43639,
     *                          "name": "1998-067PP",
     *                          "line1": "1 43639U 98067PP  18285.63087014  .00011230  00000-0  17372-3 0  9998",
     *                          "line2": "2 43639  51.6410 153.4290 0005254 236.1779 123.8756 15.54584503   933"
     *                      },
     *                      {
     *                          "id": 43640,
     *                          "name": "1998-067PQ",
     *                          "line1": "1 43640U 98067PQ  18284.79506375  .00008918  00000-0  13945-3 0  9993",
     *                          "line2": "2 43640  51.6386 157.5979 0005343 236.5215 123.5265 15.54586977   809"
     *                      }
     *                  },
     *                  "parameters": {
     *                      "search": null
     *                  },
     *                  "collection": {
     *                      "sort": "id",
     *                      "sort-dir": "asc"
     *                  },
     *                  "view": {
     *                      "@id": "http://localhost/projects/tle/api/v1/tle/?page=10&page-size=10&sort=id",
     *                      "@type": "PartialCollectionView",
     *                      "first": "http://localhost/projects/tle/api/v1/tle/?page=1&page-size=10&sort=id",
     *                      "previous": "http://localhost/projects/tle/api/v1/tle/?page=9&page-size=10&sort=id",
     *                      "next": "http://localhost/projects/tle/api/v1/tle/?page=10&page-size=10&sort=id",
     *                      "last": "http://localhost/projects/tle/api/v1/tle/?page=10&page-size=10&sort=id"
     *                  }
     *              }
     *         ),
     *     ),
     *     @OA\Response(response=500, ref="#/components/responses/500")
     * )
     */
    public function collection(Request $request, TleRepository $repository, RouterInterface $router): Response
    {
        $search = $request->get(self::SEARCH_PARAM);
        $sort = $this->getSort($request, TleRepository::SORT_NAME, TleRepository::$sort);
        $sortDir = $this->getSortDirection($request, self::SORT_ASC);

        $total = $repository->count([]);
        $pageSize = $this->getPageSize($request, TleRepository::PAGE_SIZE);
        $page = $this->getPage($request);
        $offset = $this->getPageOffset($page, $pageSize);

        $tle = $repository->search($search, $sort, $sortDir, $pageSize, $offset);

        return $this->response(
            [
                '@context' => 'http://www.w3.org/ns/hydra/context.jsonld',
                '@id' => $router->generate('tle_collection', [], UrlGeneratorInterface::ABSOLUTE_URL),
                '@type' => 'Collection',
                'totalItems' => $total,
                'member' => $this->converter->convertCollection($tle),
                'parameters' => [
                    self::SEARCH_PARAM => $search,
                ],
                'collection' => [
                    self::SORT_PARAM => $sort,
                    self::SORT_DIR_PARAM => $sortDir,
                ],
                'view' => $this->getPagination($request, 'tle_collection', $total, $pageSize),
            ]
        );
    }
}

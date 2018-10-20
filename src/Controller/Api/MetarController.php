<?php

namespace App\Controller\Api;

use App\Converter\MetarModelConverter;
use App\Repository\MetarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/metar")
 */
class MetarController extends AbstractApiController
{
    /**
     * @Route("/", name="metar_collection")
     */
    public function collection(Request $request, MetarRepository $repository, MetarModelConverter $converter): Response
    {
        $search = $request->get(self::SEARCH_PARAM);
        $sort = $this->getSort($request, MetarRepository::SORT_DATE, MetarRepository::$sort);
        $sortDir = $this->getSortDirection($request, self::SORT_DESC);
        $pageSize = $this->getPageSize($request, self::PAGE_SIZE);

        $collection = $repository->collection(
            $search,
            $sort,
            $sortDir,
            $pageSize,
            $this->getPageOffset($this->getPage($request), $pageSize)
        );

        return $this->response(
            [
                '@context' => 'http://www.w3.org/ns/hydra/context.jsonld',
                '@id' => $this->getCurrentUrl($request),
                '@type' => 'Collection',
                'totalItems' => $collection->getTotal(),
                'member' => $converter->collection($collection->getCollection()),
                'search' => $search ?? '*',
                'collection' => [
                    self::SORT_PARAM => $sort,
                    self::SORT_DIR_PARAM => $sortDir,
                ],
                'view' => $this->getPagination($request, $collection->getTotal(), $pageSize),
            ]
        );
    }
}

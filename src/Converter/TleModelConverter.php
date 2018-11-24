<?php

namespace App\Converter;

use App\Entity\Tle;
use App\Model\TleModel;
use Symfony\Component\Routing\RequestContext;

class TleModelConverter
{
    public function convert(Tle $tle): TleModel
    {
        return new TleModel($tle->getLine1(), $tle->getLine2(), $tle->getName());
    }

    /**
     * @param Tle[]          $collection
     * @param RequestContext $context
     *
     * @return TleModel[]
     */
    public function collection($collection): array
    {
        $result = [];
        foreach ($collection as $item) {
            $result[] = $this->convert($item);
        }

        return $result;
    }
}

<?php

namespace App\Converter;

use App\Entity\Tle;
use App\Model\TleModel;

class TleModelConverter
{
    public function convert(Tle $tle): TleModel
    {
        return new TleModel($tle->getLine1(), $tle->getLine2(), $tle->getName());
    }

    /**
     * @param Tle[]
     *
     * @return TleModel[]
     */
    public function convertCollection($tle): array
    {
        $result = [];
        foreach ($tle as $item) {
            $result[] = $this->convert($item);
        }

        return $result;
    }
}

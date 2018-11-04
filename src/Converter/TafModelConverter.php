<?php

namespace App\Converter;

use App\Entity\Metar;
use App\Model\TafModel;
use TafDecoder\TafDecoder;

class TafModelConverter
{
    /** @var TafDecoder */
    private $decoder;

    public function __construct()
    {
        $this->decoder = new TafDecoder();
    }

    public function convert(Metar $metar): TafModel
    {
        $decoded = $this->decoder->parse($metar->getMetar());

        $taf = new TafModel();
        $taf->setIcao($decoded->getIcao());

        return $taf;
    }

    /**
     * @param Metar[] $collection
     *
     * @return TafModel[]
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

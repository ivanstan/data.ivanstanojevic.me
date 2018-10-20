<?php

namespace App\Converter;

use App\Entity\Metar;
use App\Model\MetarModel;
use MetarDecoder\MetarDecoder;

class MetarModelConverter
{
    /** @var MetarDecoder */
    private $decoder;

    public function __construct()
    {
        $this->decoder = new MetarDecoder();
    }

    public function convert(Metar $metar): MetarModel
    {
        $decoded = $this->decoder->parse($metar->getMetar());

        $model = new MetarModel();
        $model->setRaw($metar->getMetar());

        return $model;
    }

    /**
     * @param Metar[] $collection
     *
     * @return Metar[]
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

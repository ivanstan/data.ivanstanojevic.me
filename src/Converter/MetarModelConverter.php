<?php

namespace App\Converter;

use App\Entity\Metar;
use App\Model\MetarModel;
use App\Model\ValueUnit;
use MetarDecoder\Entity\Value;
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
        $model->setIcao($metar->getIcao());
        $model->setDate($metar->getDate()->format('c'));
        $model->setRaw($metar->getMetar());

        /** @var Value $temperature */
        $temperature = $decoded->getAirTemperature();

        if ($temperature) {
            $temperatureModel = new ValueUnit();
            $temperatureModel->setValue($temperature->getValue());
            $temperatureModel->setUnit($temperature->getUnit());
            $model->setTemperature($temperatureModel);
        }

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

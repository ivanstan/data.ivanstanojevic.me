<?php /** @noinspection PhpAssignmentInConditionInspection */

namespace App\Service\Converter;

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

    public function convert(?Metar $metar): ?MetarModel
    {
        if ($metar === null) {
            return null;
        }

        $decoded = $this->decoder->parse($metar->getMetar());

        $model = new MetarModel();
        $model->setIcao($metar->getIcao());
        $model->setDate($metar->getDate()->format('c'));
        $model->setRaw($metar->getMetar());

        /** @var Value $temperature */
        if ($temperature = $decoded->getAirTemperature()) {
            $model->setTemperature(new ValueUnit($temperature->getValue(), $temperature->getUnit()));
        }

        /** @var Value $dew */
        if ($dew = $decoded->getDewPointTemperature()) {
            $model->setDewPoint(new ValueUnit($dew->getValue(), $dew->getUnit()));
        }

        /** @var Value $pressure */
        if ($pressure = $decoded->getPressure()) {
            $model->setPressure(new ValueUnit($pressure->getValue(), $pressure->getUnit()));
        }

        return $model;
    }

    /**
     * @param Metar[] $collection
     *
     * @return MetarModel[]
     */
    public function collection($collection): array
    {
        $result = [];
        foreach ($collection as $item) {
            if ($item = $this->convert($item)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}

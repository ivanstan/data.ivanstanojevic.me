<?php /** @noinspection PhpAssignmentInConditionInspection */

namespace App\Converter;

use App\Entity\Metar;
use App\Model\TafModel;
use App\Model\ValueUnit;
use TafDecoder\Entity\ForecastPeriod;
use TafDecoder\Entity\Value;
use TafDecoder\TafDecoder;

class TafModelConverter
{
    /** @var TafDecoder */
    private $decoder;

    public function __construct()
    {
        $this->decoder = new TafDecoder();
    }

    public function convert(Metar $metar): ?TafModel
    {
        $decoded = $this->decoder->parse($metar->getMetar());

        $model = new TafModel();
        $model->setIcao($decoded->getIcao());

        /** @var ForecastPeriod $period */
        $period = $decoded->getForecastPeriod();

        if ($period === null) {
            return null;
        }

        $date = $metar->getDate() or new \DateTime();
        $date->setDate($date->format('Y'), $date->format('m'), $period->getToDay());
        $date->setTime($period->getToHour(), $date->format('i'));

        $model->setDate($date->format('c'));

        /** @var Value $temperature */
        if ($temperature = $decoded->getMinTemperature()->getTemperature()) {
            $model->setMinTemperature(new ValueUnit($temperature->getValue(), $temperature->getUnit()));
        }

        /** @var Value $temperature */
        if ($temperature = $decoded->getMaxTemperature()->getTemperature()) {
            $model->setMaxTemperature(new ValueUnit($temperature->getValue(), $temperature->getUnit()));
        }

        return $model;
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
            $taf = $this->convert($item);

            if ($taf !== null) {
                $result[] = $taf;
            }
        }

        return $result;
    }
}

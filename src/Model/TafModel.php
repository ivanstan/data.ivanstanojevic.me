<?php

namespace App\Model;

use App\Field\DateIsoField;
use App\Field\IcaoField;

class TafModel
{
    use IcaoField;
    use DateIsoField;

    /** @var ValueUnit */
    private $minTemperature;

    /** @var ValueUnit */
    private $maxTemperature;

    public function getMinTemperature(): ValueUnit
    {
        return $this->minTemperature;
    }

    public function setMinTemperature(ValueUnit $minTemperature): void
    {
        $this->minTemperature = $minTemperature;
    }

    public function getMaxTemperature(): ValueUnit
    {
        return $this->maxTemperature;
    }

    public function setMaxTemperature(ValueUnit $maxTemperature): void
    {
        $this->maxTemperature = $maxTemperature;
    }
}

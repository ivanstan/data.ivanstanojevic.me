<?php

namespace App\Model;

use App\Entity\Field\IcaoField;

class MetarModel
{
    public const DEGREES_CELSIUS = 'deg C';

    use IcaoField;

    /** @var string */
    private $date;

    /** @var ValueUnit */
    private $temperature;

    /** @var ValueUnit */
    private $dewPoint;

    /** @var ValueUnit */
    private $pressure;

    /** @var string */
    private $raw;

    /**
     * MetarModel constructor.
     */
    public function __construct()
    {
        $this->temperature = new ValueUnit(null, self::DEGREES_CELSIUS);
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getTemperature(): ?ValueUnit
    {
        return $this->temperature;
    }

    public function setTemperature(ValueUnit $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function getDewPoint(): ?ValueUnit
    {
        return $this->dewPoint;
    }

    public function setDewPoint(ValueUnit $dewPoint): void
    {
        $this->dewPoint = $dewPoint;
    }

    public function getPressure(): ?ValueUnit
    {
        return $this->pressure;
    }

    public function setPressure(ValueUnit $pressure): void
    {
        $this->pressure = $pressure;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): void
    {
        $this->raw = $raw;
    }
}

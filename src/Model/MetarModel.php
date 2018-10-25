<?php

namespace App\Model;

class MetarModel
{
    /** @var string */
    private $icao;

    /** @var string */
    private $date;

    /** @var ValueUnit */
    private $temperature;

    /** @var string */
    private $raw;

    public function getIcao(): string
    {
        return $this->icao;
    }

    public function setIcao(string $icao): void
    {
        $this->icao = $icao;
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

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): void
    {
        $this->raw = $raw;
    }
}

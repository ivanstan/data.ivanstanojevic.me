<?php

namespace App\Entity;

use App\Field\CityField;
use App\Field\CountryField;
use App\Field\IataField;
use App\Field\IcaoField;
use App\Field\IdField;
use App\Field\LatLngField;
use App\Field\NameField;
use App\Field\TypeField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(indexes={@ORM\Index(name="designator", columns={"icao", "iata"})})
 * @see https://openflights.org/data.html
 */
class Airport
{
    use IdField;
    use NameField;
    use CityField;
    use CountryField;
    use LatLngField;
    use TypeField;
    use IataField;
    use IcaoField;

    /**
     * @var float
     * @ORM\Column(name="altitude", type="decimal", scale=2)
     */
    private $altitude;

    /**
     * @var float
     * @ORM\Column(name="utc_offset", type="decimal", scale=2)
     */
    private $utcOffset;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string")
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string")
     */
    private $timezone;

    /**
     * @var string
     *
     * @ORM\Column(name="dst", type="string")
     */
    private $dst;

    public function getAltitude(): float
    {
        return $this->altitude;
    }

    public function setAltitude(float $altitude): void
    {
        $this->altitude = $altitude;
    }

    public function getUtcOffset(): float
    {
        return $this->utcOffset;
    }

    public function setUtcOffset(float $utcOffset): void
    {
        $this->utcOffset = $utcOffset;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getDst(): string
    {
        return $this->dst;
    }

    public function setDst(string $dst): void
    {
        $this->dst = $dst;
    }
}

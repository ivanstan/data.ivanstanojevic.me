<?php

namespace App\Entity;

use App\Field\IdField;
use App\Field\LatLng;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Firms
{
    use IdField;

    public const CONFIDENCE_LOW = 'l';
    public const CONFIDENCE_NOMINAL = 'f';
    public const CONFIDENCE_HIGH = 'h';

    public const SATELLITE_AQUA = 'AQUA';
    public const SATELLITE_TERRA = 'TERRA';

    /**
     * @var LatLng
     * @ORM\Column(name="point", type="latlng", nullable=false)
     */
    private $point;

    /**
     * @var float
     * @ORM\Column(
     *     name="brightness",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "Channel 21/22 brightness temperature of the fire pixel measured in Kelvin."}
     *     )
     */
    private $brightness;

    /**
     * @var float
     * @ORM\Column(
     *     name="brightness31",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "Channel 31 brightness temperature of the fire pixel measured in Kelvin."}
     *     )
     */
    private $brightness31;

    /**
     * @var float
     * @ORM\Column(
     *     name="power",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "Depicts the pixel-integrated fire radiative power in megawatts."}
     * )
     */
    private $power;

    /**
     * @var bool
     * @ORM\Column(name="daytime", type="boolean", nullable=true)
     */
    private $daytime;

    /**
     * @var string
     * @ORM\Column(name="satellite", type="string", nullable=true)
     */
    private $satellite;

    /**
     * @var float
     * @ORM\Column(
     *     name="track",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "The algorithm produces 1km fire pixels but MODIS
     *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel
     *                          size."}
     *     )
     */
    private $track;

    /**
     * @var float
     * @ORM\Column(
     *     name="scan",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "The algorithm produces 1km fire pixels but MODIS
     *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel size."}
     * )
     */
    private $scan;

    /**
     * @var \DateTime $date
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="confidence", type="string", nullable=true)
     */
    private $confidence;

    /**
     * @var string
     * @ORM\Column(name="instrument", type="string", nullable=true)
     */
    private $instrument;

    /**
     * @var string
     * @ORM\Column(name="version", type="string", nullable=true)
     */
    private $version;

    public function getPoint(): LatLng
    {
        return $this->point;
    }

    public function setPoint(LatLng $point): void
    {
        $this->point = $point;
    }

    public function getBrightness(): float
    {
        return $this->brightness;
    }

    public function setBrightness(float $brightness): void
    {
        $this->brightness = $brightness;
    }

    public function getBrightness31(): float
    {
        return $this->brightness31;
    }

    public function setBrightness31(float $brightness31): void
    {
        $this->brightness31 = $brightness31;
    }

    public function getPower(): float
    {
        return $this->power;
    }

    public function setPower(float $power): void
    {
        $this->power = $power;
    }

    public function isDaytime(): bool
    {
        return $this->daytime;
    }

    public function setDaytime(bool $daytime): void
    {
        $this->daytime = $daytime;
    }

    public function getSatellite(): string
    {
        return $this->satellite;
    }

    public function setSatellite(string $satellite): void
    {
        $this->satellite = $satellite;
    }

    public function getTrack(): float
    {
        return $this->track;
    }

    public function setTrack(float $track): void
    {
        $this->track = $track;
    }

    public function getScan(): float
    {
        return $this->scan;
    }

    public function setScan(float $scan): void
    {
        $this->scan = $scan;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getConfidence(): string
    {
        return $this->confidence;
    }

    public function setConfidence(string $confidence): void
    {
        $this->confidence = $confidence;
    }

    public function getInstrument(): string
    {
        return $this->instrument;
    }

    public function setInstrument(string $instrument): void
    {
        $this->instrument = $instrument;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}

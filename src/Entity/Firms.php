<?php

namespace App\Entity;

use App\Entity\Field\DateField;
use App\Entity\Field\IdField;
use App\Entity\Field\LatLngField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FirmsRepository")
 */
class Firms
{
    use IdField;
    use LatLngField;
    use DateField;

    /**
     * @var float
     * @ORM\Column(
     *     name="brightness",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "Channel I-4 for VIIRS and Channel 21/22 for MODIS."}
     * )
     */
    private $brightness1;

    /**
     * @var float
     * @ORM\Column(
     *     name="brightness31",
     *     type="decimal",
     *     scale=2,
     *     nullable=true,
     *     options={"comment": "Channel I-5 for VIIRS and Channel 31 for MODIS"}
     * )
     */
    private $brightness2;

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
     * )
     */
    private $scan;

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

    public function getBrightness1(): float
    {
        return $this->brightness1;
    }

    public function setBrightness1(float $brightness): void
    {
        $this->brightness1 = $brightness;
    }

    public function getBrightness2(): float
    {
        return $this->brightness2;
    }

    public function setBrightness2(float $brightness): void
    {
        $this->brightness2 = $brightness;
    }

    public function getPower(): float
    {
        return $this->power;
    }

    public function setPower(float $power): void
    {
        $this->power = $power;
    }

    public function isDaytime(): ?bool
    {
        return $this->daytime;
    }

    public function setDaytime(bool $daytime): void
    {
        $this->daytime = $daytime;
    }

    public function getSatellite(): ?string
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

    public function getConfidence(): string
    {
        return $this->confidence;
    }

    public function setConfidence(string $confidence): void
    {
        $this->confidence = $confidence;
    }

    public function getInstrument(): ?string
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

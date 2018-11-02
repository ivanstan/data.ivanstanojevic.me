<?php

namespace App\Field;

trait LatLng
{
    /**
     * @var float
     * @ORM\Column(name="latitude", type="decimal", scale=8, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(name="longitude", type="decimal", scale=8, nullable=false)
     */
    private $longitude;

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }
}

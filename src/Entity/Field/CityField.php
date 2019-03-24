<?php

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

trait CityField
{
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
}

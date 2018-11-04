<?php

namespace App\Field;

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

    public function setCity(string $name): void
    {
        $this->city = $name;
    }
}

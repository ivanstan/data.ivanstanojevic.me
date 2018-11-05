<?php

namespace App\Field;

use Doctrine\ORM\Mapping as ORM;

trait IataField
{
    /**
     * @var string
     *
     * @ORM\Column(name="iata", type="string", length=3)
     */
    private $iata;

    public function getIata(): string
    {
        return $this->iata;
    }

    public function setIata(string $iata): void
    {
        $this->iata = $iata;
    }
}

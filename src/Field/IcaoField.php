<?php

namespace App\Field;

use Doctrine\ORM\Mapping as ORM;

trait IcaoField
{
    /**
     * @var string
     *
     * @ORM\Column(name="icao", type="string", length=4)
     */
    private $icao;

    public function getIcao(): string
    {
        return $this->icao;
    }

    public function setIcao(string $icao): void
    {
        $this->icao = $icao;
    }
}

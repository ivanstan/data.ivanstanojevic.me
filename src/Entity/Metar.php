<?php

namespace App\Entity;

use App\Field\IdField;
use App\Field\TypeField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetarRepository")
 */
class Metar
{
    use IdField;
    use TypeField;

    public const TYPE_METAR = 'METAR';
    public const TYPE_TAF = 'TAF';

    /**
     * @var string
     *
     * @ORM\Column(name="icao", type="string")
     */
    private $icao;

    /**
     * @var \DateTime $date
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="metar", type="text")
     */
    private $metar;

    public function getIcao(): string
    {
        return $this->icao;
    }

    public function setIcao(string $icao): void
    {
        $this->icao = $icao;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getMetar(): string
    {
        return $this->metar;
    }

    public function setMetar(string $metar): void
    {
        $this->metar = $metar;
    }
}

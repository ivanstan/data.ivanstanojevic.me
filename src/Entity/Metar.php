<?php

namespace App\Entity;

use App\Field\DateField;
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
    use DateField;

    public const TYPE_METAR = 'METAR';
    public const TYPE_TAF = 'TAF';

    /**
     * @var string
     *
     * @ORM\Column(name="icao", type="string")
     */
    private $icao;

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

    public function getMetar(): string
    {
        return $this->metar;
    }

    public function setMetar(string $metar): void
    {
        $this->metar = $metar;
    }
}

<?php

namespace App\Entity;

use App\Field\DateField;
use App\Field\IdField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Pollen
{
    use IdField;
    use DateField;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PollenType")
     * @ORM\JoinColumn(name="pollen_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var integer
     * @ORM\Column(name="tendency", type="integer", nullable=true)
     */
    private $tendency;

    /**
     * @var integer
     * @ORM\Column(name="concentration", type="integer", nullable=true)
     */
    private $concentration;

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getTendency(): ?int
    {
        return $this->tendency;
    }

    public function setTendency(?int $tendency): void
    {
        $this->tendency = $tendency;
    }

    public function getConcentration(): ?int
    {
        return $this->concentration;
    }

    public function setConcentration(?int $concentration): void
    {
        $this->concentration = $concentration;
    }
}

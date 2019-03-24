<?php

namespace App\Entity;

use App\Entity\Field\DescriptionField;
use App\Entity\Field\FrequencyField;
use App\Entity\Field\IdField;
use App\Entity\Field\TypeField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Frequency
{
    use IdField;
    use TypeField;
    use DescriptionField;
    use FrequencyField;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="frequencies")
     * @ORM\JoinColumn(name="airport_id", referencedColumnName="id")
     */
    private $airport;

    public function getAirport(): Airport
    {
        return $this->airport;
    }

    public function setAirport($airport): void
    {
        $this->airport = $airport;
    }
}

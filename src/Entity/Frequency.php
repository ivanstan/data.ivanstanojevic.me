<?php

namespace App\Entity;

use App\Field\DescriptionField;
use App\Field\FrequencyField;
use App\Field\IdField;
use App\Field\TypeField;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport")
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

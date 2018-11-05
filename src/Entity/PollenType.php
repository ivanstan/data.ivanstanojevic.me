<?php


namespace App\Entity;

use App\Field\IdField;
use App\Field\NameField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pollen_type")
 */
class PollenType
{
    use IdField;
    use NameField;

    /**
     * @var string
     *
     * @ORM\Column(name="`group`", type="string")
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="potential", type="integer")
     */
    private $potential;

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    public function getPotential(): string
    {
        return $this->potential;
    }

    public function setPotential(string $potential): void
    {
        $this->potential = $potential;
    }
}

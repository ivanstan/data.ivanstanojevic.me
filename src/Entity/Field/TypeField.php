<?php


namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

trait TypeField
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}

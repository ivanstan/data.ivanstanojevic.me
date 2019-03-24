<?php

namespace App\Entity\Field;

use Doctrine\ORM\Mapping as ORM;

trait DescriptionField
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}

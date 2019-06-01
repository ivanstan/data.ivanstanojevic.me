<?php

namespace App\Entity\Traits;

trait CreatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function getCreatedAt(): \DateTime
    {
        return $this->created;
    }

    public function setCreatedAt(\DateTime $created): void
    {
        $this->created = $created;
    }
}

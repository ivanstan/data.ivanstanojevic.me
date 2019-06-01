<?php

namespace App\Entity\Traits;

trait UpdatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated;
    }

    public function setUpdatedAt(\DateTime $updated): void
    {
        $this->updated = $updated;
    }
}

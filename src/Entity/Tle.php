<?php

namespace App\Entity;

use App\Field\NameField;
use App\Field\TleField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tle
{
    use NameField;
    use TleField;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="satellite_id", type="integer")
     */
    private $satelliteId;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function update(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getSatelliteId(): int
    {
        return $this->satelliteId;
    }

    public function setSatelliteId(int $satelliteId): void
    {
        $this->satelliteId = $satelliteId;
    }
}

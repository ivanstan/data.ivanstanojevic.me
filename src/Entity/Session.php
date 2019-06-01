<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Session
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="sessions")
     */
    private $user;

    /**
     * @var mixed
     * @ORM\Column(type="blob", nullable=true)
     */
    private $data;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var \DateInterval
     * @ORM\Column(type="dateinterval")
     */
    private $lifetime;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $connection): void
    {
        $this->user = $connection;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getLifetime(): \DateInterval
    {
        return $this->lifetime;
    }

    public function setLifetime(\DateInterval $lifetime): void
    {
        $this->lifetime = $lifetime;
    }
}

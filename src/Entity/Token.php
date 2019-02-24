<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Token
{
    public const TYPE_VERIFY = 'verify';
    public const TYPE_RECOVER = 'recover';

    private const TOKEN_LENGTH = 24;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @throws \Exception
     */
    public function __construct(User $user, string $type = self::TYPE_RECOVER)
    {
        $this->dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $this->type = $type;
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }
}

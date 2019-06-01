<?php

namespace App\Entity\Token;

use App\Entity\Traits\CreatedTrait;
use App\Security\TokenGenerator;
use App\Service\DateTimeService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     UserToken::TYPE = "App\Entity\Token\UserToken",
 * })
 */
abstract class Token
{
    use CreatedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $token;

    /**
     * @var \DateInterval
     * @ORM\Column(name="`interval`", type="dateinterval")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $interval;

    /**
     * @throws \Exception
     */
    public function __construct(
        string $interval = TokenGenerator::TOKEN_INTERVAL,
        int $length = TokenGenerator::TOKEN_LENGTH
    ) {
        $this->created = DateTimeService::getCurrentUTC();
        $this->token = TokenGenerator::generate($length);
        $this->interval = new \DateInterval($interval);
    }

    /**
     * @throws \Exception
     */
    public function isValid(\DateTime $date): bool
    {
        return $this->getCreatedAt()->add($this->interval) >= $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getInterval(): \DateInterval
    {
        return $this->interval;
    }

    public function setInterval(\DateInterval $interval): void
    {
        $this->interval = $interval;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WatchdogRepository")
 */
class Watchdog
{
    public const EMERGENCY = 0;
    public const ALERT = 1;
    public const CRITICAL = 2;
    public const ERROR = 3;
    public const WARNING = 4;
    public const NOTICE = 5;
    public const INFO = 6;
    public const DEBUG = 7;

    private static $severityMap = [
        self::EMERGENCY => 'Emergency',
        self::ALERT => 'Alert',
        self::CRITICAL => 'Critical',
        self::ERROR => 'Error',
        self::WARNING => 'Warning',
        self::NOTICE => 'Notice',
        self::INFO => 'Info',
        self::DEBUG => 'Debug',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateTime;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default"=5})
     */
    private $severity;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $data;

    public function __construct()
    {
        $this->dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->severity = self::NOTICE;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getSeverity(): int
    {
        return $this->severity;
    }

    public function getSeverityString(): string
    {
        return self::$severityMap[$this->severity] ?? self::$severityMap[self::NOTICE];
    }

    public function setSeverity(int $severity): void
    {
        $this->severity = $severity;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }
}

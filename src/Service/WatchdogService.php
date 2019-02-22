<?php

namespace App\Service;

use App\Entity\Watchdog;
use Doctrine\ORM\EntityManagerInterface;

class WatchdogService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function log(string $type, string $message, $data = null, $severity = Watchdog::NOTICE): void
    {
        $log = new Watchdog();
        $log->setType($type);
        $log->setMessage($message);
        $log->setSeverity($severity);
        $log->setData($data);

        $this->em->persist($log);
        $this->em->flush();
    }
}

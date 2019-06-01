<?php

namespace App\Event\Doctrine;

use App\Service\DateTimeService;
use Doctrine\DBAL\Event\ConnectionEventArgs;

class DoctrineListener
{
    public function postConnect(ConnectionEventArgs $event): void
    {
        $event->getConnection()->exec("SET time_zone = '".DateTimeService::UTC_TIMEZONE_OFFSET."'");
    }
}

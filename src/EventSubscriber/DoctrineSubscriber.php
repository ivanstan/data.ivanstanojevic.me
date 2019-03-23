<?php

namespace App\EventSubscriber;

use App\Service\System\DateTimeService;
use Doctrine\DBAL\Event\ConnectionEventArgs;

class DoctrineSubscriber
{
    public function postConnect(ConnectionEventArgs $event): void
    {
        $event->getConnection()->exec("SET time_zone = '".DateTimeService::UTC_TIMEZONE_OFFSET."'");
    }
}

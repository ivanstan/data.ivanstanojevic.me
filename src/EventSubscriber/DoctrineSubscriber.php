<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Event\ConnectionEventArgs;

class DoctrineSubscriber
{
    public const MYSQL_TIMEZONE = '+0:00';

    public function postConnect(ConnectionEventArgs $event): void
    {
        $event->getConnection()->exec("SET time_zone = '".self::MYSQL_TIMEZONE."'");
    }
}

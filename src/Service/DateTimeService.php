<?php

namespace App\Service;

use App\Kernel;

class DateTimeService
{
    public const UTC_TIMEZONE_NAME = 'UTC';

    public const UTC_TIMEZONE_OFFSET = '+0:00';

    public static function getCurrentUTC(): \DateTime
    {
        if (Kernel::DEV === $_ENV['APP_ENV']) {
            $modify = new \DateInterval('P7D');

            return (new \DateTime('now', new \DateTimeZone(self::UTC_TIMEZONE_NAME)))->add($modify);
        }

        return new \DateTime('now', new \DateTimeZone(self::UTC_TIMEZONE_NAME));
    }
}

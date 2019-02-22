<?php

namespace App\Service;

trait WatchdogAwareTrait
{
    /** @var WatchdogService */
    private $watchdog;

    /**
     * @required
     */
    public function seWatchdogService(WatchdogService $service): void
    {
        $this->watchdog = $service;
    }
}

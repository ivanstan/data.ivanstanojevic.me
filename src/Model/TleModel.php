<?php /** @noinspection SummerTimeUnsafeTimeManipulationInspection */

namespace App\Model;

use App\Field\NameField;
use App\Field\TleField;

class TleModel
{
    use NameField;
    use TleField;

    public function __construct(string $line1, string $line2, string $name = null)
    {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->name = $name;
    }

    public function getId(): int
    {
        return (int)substr($this->line1, 2, 6);
    }

    public function getDate(): string
    {
        $year = (int)trim(substr($this->line1, 18, 2));

        if ($year < 57) {
            $year += 2000;
        } else {
            $year += 1900;
        }

        $date = new \DateTime();
        $timezone = new \DateTimeZone('UTC');

        $epoch = (float)trim(substr($this->line1, 20, 12));
        $days = (int)$epoch;

        $date
            ->setTimezone($timezone)
            ->setDate($year, 1, $days);

        $faction = round($epoch - $days, 8);

        $faction *= 24; // hours
        $hours = round($faction);
        $faction -= $hours;

        $faction *= 60; // minutes
        $minutes = round($faction);
        $faction -= $minutes;

        $faction *= 60; // seconds
        $seconds = round($faction);
        $faction -= $seconds;

        $faction *= 1000; // milliseconds
        $milliseconds = round($faction);

        $date->setTime($hours, $minutes, $seconds, $milliseconds);

        return $date->format('c');
    }
}

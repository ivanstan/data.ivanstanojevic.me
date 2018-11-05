<?php

namespace App\Field;

trait FrequencyField
{
    /**
     * @var float
     * @ORM\Column(name="frequency", type="decimal", scale=2)
     */
    private $frequency;

    public function getFrequency(): float
    {
        return $this->frequency;
    }

    public function setFrequency(float $frequency): void
    {
        $this->frequency = $frequency;
    }
}

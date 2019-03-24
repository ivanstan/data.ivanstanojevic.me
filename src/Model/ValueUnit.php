<?php

namespace App\Model;

class ValueUnit
{
    /** @var float */
    private $value;

    /** @var string */
    private $unit;

    public function __construct(?float $value, ?string $unit)
    {
        $this->value = $value;
        $this->unit = $unit;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }
}

<?php

namespace App\Model;

class MetarModel
{
    /** @var string */
    private $raw;

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function setRaw(string $raw): void
    {
        $this->raw = $raw;
    }
}

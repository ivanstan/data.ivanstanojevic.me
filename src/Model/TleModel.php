<?php

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
}

<?php

namespace App\Field;

use Doctrine\ORM\Mapping as ORM;

trait IdField
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }
}

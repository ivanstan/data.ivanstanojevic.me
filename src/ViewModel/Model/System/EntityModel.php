<?php

namespace App\ViewModel\Model\System;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class EntityModel
{
    /**
     * @var string
     * @SerializedName("@id")
     */
    private $uri;

    /**
     * @var string
     * @SerializedName("@type")
     */
    private $type;

    /**
     * @var int
     * @Groups({"api_course_instance"})
     */
    private $id;

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): EntityModel
    {
        $this->uri = $uri;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}

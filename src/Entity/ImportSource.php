<?php

namespace App\Entity;

use App\Field\IdField;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ImportSource
{
    use IdField;

    public const TYPE_FIRMS = 'FIRMS';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="text")
     */
    private $uri;

    /**
     * @var string
     *
     * @ORM\Column(name="sha1", type="text")
     */
    private $sha1;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getSha1(): string
    {
        return $this->sha1;
    }

    public function setSha1(string $sha1): void
    {
        $this->sha1 = $sha1;
    }
}

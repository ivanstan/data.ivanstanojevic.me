<?php

namespace App\ViewModel\Model\Api;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PaginationNavigation
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
    private static $type = 'PartialCollectionView';

    /** @var string */
    private $first;

    /** @var string */
    private $previous;

    /** @var string */
    private $next;

    /** @var string */
    private $last;

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): PaginationNavigation
    {
        $this->uri = $uri;

        return $this;
    }

    public function getType(): string
    {
        return self::$type;
    }

    public function getFirst(): ?string
    {
        return $this->first;
    }

    public function setFirst(?string $first): PaginationNavigation
    {
        $this->first = $first;

        return $this;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    public function setPrevious(?string $previous): PaginationNavigation
    {
        $this->previous = $previous;

        return $this;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): PaginationNavigation
    {
        $this->next = $next;

        return $this;
    }

    public function getLast(): ?string
    {
        return $this->last;
    }

    public function setLast(?string $last): PaginationNavigation
    {
        $this->last = $last;

        return $this;
    }
}

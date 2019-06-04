<?php

namespace App\ViewModel\Model\Api;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PaginationCollection
{
    /**
     * @var string
     * @SerializedName("@type")
     */
    private static $type = 'Collection';

    /**
     * @var string
     * @SerializedName("@id")
     */
    private $uri;

    /**
     * @var array
     * @SerializedName("member")
     */
    private $collection = [];

    /** @var array */
    private $parameters = [];

    /**
     * @var int
     * @SerializedName("totalItems")
     */
    private $total = 0;

    /**
     * @var PaginationNavigation
     * @SerializedName("view")
     */
    private $navigation;

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::$type;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): PaginationCollection
    {
        $this->uri = $uri;

        return $this;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameter(string $name, string $value): self
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function getNavigation(): PaginationNavigation
    {
        return $this->navigation;
    }

    public function setNavigation(PaginationNavigation $navigation): PaginationCollection
    {
        $this->navigation = $navigation;

        return $this;
    }
}

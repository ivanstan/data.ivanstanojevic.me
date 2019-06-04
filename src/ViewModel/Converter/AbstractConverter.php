<?php

namespace App\ViewModel\Converter;

use App\ViewModel\ConverterInterface;

abstract class AbstractConverter implements ConverterInterface
{
    protected $routeName;

    abstract public function supports($entity): bool;

    abstract public function convert($entity);

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }
}

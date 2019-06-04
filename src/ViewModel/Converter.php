<?php

namespace App\ViewModel;

class Converter
{
    /**
     * @var ConverterInterface[]
     */
    private $converters = [];

    public function addConverter(ConverterInterface $converter): void
    {
        $this->converters[] = $converter;
    }

    public function convert($entity)
    {
        foreach ($this->converters as $converter) {
            if ($converter->supports($entity)) {
                return $converter->convert($entity);
            }
        }

        return null;
    }

    public function convertCollection(array $collection): array
    {
        foreach ($collection as &$item) {
            $item = $this->convert($item);
        }

        return $collection;
    }
}

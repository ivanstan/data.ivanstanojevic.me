<?php

namespace App\ViewModel;

interface ConverterInterface
{
    public const ITEM_ROUTE = 'collection_route';

    public const COLLECTION_ROUTE = 'item_route';

    public function supports($entity): bool;

    public function convert($entity);
}

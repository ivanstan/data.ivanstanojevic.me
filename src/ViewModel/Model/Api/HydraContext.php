<?php

namespace App\ViewModel\Model\Api;

use Symfony\Component\Serializer\Annotation\SerializedName;

class HydraContext
{
    /**
     * @var string
     * @SerializedName("@context")
     */
    private static $context = 'http://www.w3.org/ns/hydra/context.jsonld';

    public function getContext(): string
    {
        return self::$context;
    }
}

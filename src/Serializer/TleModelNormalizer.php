<?php

namespace App\Serializer;

use App\Model\TleModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TleModelNormalizer implements NormalizerInterface
{
    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param TleModel $model
     * @param null     $format
     * @param array    $context
     *
     * @return array|bool|float|int|string
     */
    public function normalize($model, $format = null, array $context = [])
    {
        $id = $this->router->generate('tle_record', ['id' => $model->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);

        return [
            '@id' => $id,
            '@type' => 'TleModel',
            'satelliteId' => $model->getId(),
            'name' => $model->getName(),
            'date' => $model->getDate(),
            'line1' => $model->getLine1(),
            'line2' => $model->getLine2(),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof TleModel;
    }
}

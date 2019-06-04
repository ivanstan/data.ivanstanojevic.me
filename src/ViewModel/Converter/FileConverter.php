<?php

namespace App\ViewModel\Converter;

use App\Entity\System\File;
use App\Service\Traits\RouterAwareTrait;
use App\ViewModel\Converter;
use App\ViewModel\Model\System\FileModel;

class FileConverter extends AbstractConverter
{
    use RouterAwareTrait;
    /** @var Converter */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function supports($entity): bool
    {
        return $entity instanceof File;
    }

    /**
     * @param File $entity
     */
    public function convert($entity): FileModel
    {
        $model = new FileModel();

        $model->setId($entity->getId());
        $model->setDestination($entity->getDestination());
        $model->setMime($entity->getMime());
        $model->setSize($entity->getSize());
        $model->setUser($this->converter->convert($entity->getUser()));

        return $model;
    }
}

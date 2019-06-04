<?php

namespace App\ViewModel\Converter;

use App\Entity\System\User;
use App\Service\Traits\RouterAwareTrait;
use App\ViewModel\Model\System\UserModel;

class UserConverter extends AbstractConverter
{
    use RouterAwareTrait;

    protected $routeName = 'api_user';

    public function supports($entity): bool
    {
        return $entity instanceof User;
    }

    /**
     * @param User $entity
     */
    public function convert($entity): UserModel
    {
        $model = new UserModel();

        $model->setUri($this->absoluteUrl($this->getRouteName(), ['user' => $entity->getId()]));
        $model->setId($entity->getId());
        $model->setType('User');
        $model->setEmail($entity->getEmail());

        return $model;
    }
}

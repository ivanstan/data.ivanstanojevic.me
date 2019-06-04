<?php

namespace App\ViewModel\Model\System;

use Symfony\Component\Serializer\Annotation\Groups;

class UserModel extends EntityModel
{
    /**
     * @var string
     * @Groups({"api_course_instance"})
     */
    private $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserModel
    {
        $this->email = $email;

        return $this;
    }
}

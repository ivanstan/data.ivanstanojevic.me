<?php

namespace App\Entity\Token;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserVerificationToken extends UserToken
{
    public const TYPE = 'verification';
}

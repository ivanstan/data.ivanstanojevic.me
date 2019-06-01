<?php

namespace App\Entity\Token;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserRecoveryToken extends UserToken
{
    public const TYPE = 'recovery';
}

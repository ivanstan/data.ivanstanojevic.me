<?php

namespace App\Entity\Token;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserInvitationToken extends UserToken
{
    public const TYPE = 'invitation';
}

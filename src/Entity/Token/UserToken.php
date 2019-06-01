<?php

namespace App\Entity\Token;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     UserVerificationToken::TYPE = "App\Entity\Token\UserVerificationToken",
 *     UserRecoveryToken::TYPE = "App\Entity\Token\UserRecoveryToken",
 *     UserInvitationToken::TYPE = "App\Entity\Token\UserInvitationToken",
 * })
 */
abstract class UserToken extends Token
{
    public const TYPE = 'user';

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @throws \Exception
     */
    public function __construct(User $user = null)
    {
        parent::__construct();

        if ($user !== null) {
            $this->user = $user;
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

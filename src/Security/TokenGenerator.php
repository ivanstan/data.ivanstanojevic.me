<?php

namespace App\Security;

use App\Entity\Token\Token;
use App\Entity\Token\UserInvitationToken;
use App\Entity\Token\UserRecoveryToken;
use App\Entity\Token\UserVerificationToken;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TokenGenerator
{
    public const TOKEN_LENGTH = 24;
    public const TOKEN_INTERVAL = 'P1D';

    /** @var UrlGeneratorInterface */
    private $generator;

    private static $pathNameMap = [
        UserInvitationToken::class => 'security_invitation_token',
        UserVerificationToken::class => 'security_verification_token',
        UserRecoveryToken::class => 'security_recovery_token',
    ];

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public static function generate(int $length): string
    {
        return bin2hex(random_bytes($length));
    }

    public function generateUrl(Token $token): ?string
    {
        $pathName = self::$pathNameMap[get_class($token)] ?? null;

        if ($pathName === null) {
            return null;
        }

        return $this->generator->generate(
            $pathName,
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}

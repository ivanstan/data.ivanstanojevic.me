<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Service\Traits\TranslatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    use TranslatorAwareTrait;

    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var CsrfTokenManagerInterface */
    private $tokenManager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router,
        CsrfTokenManagerInterface $tokenManager,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->tokenManager = $tokenManager;
        $this->encoder = $encoder;
    }

    public function supports(Request $request): bool
    {
        return 'security_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->tokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException($this->translator->trans('misc.messages.invalid_csrf'));
        }

        $user = $this->em->getRepository(User::class)->findByEmail($credentials['email']);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException($this->translator->trans('login.messages.bad_credentials'));
        }

        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException($this->translator->trans('login.messages.user_blocked'));
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_index'));
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate('security_login');
    }
}

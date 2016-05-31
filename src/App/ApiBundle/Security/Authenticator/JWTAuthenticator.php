<?php

namespace App\ApiBundle\Security\Authenticator;

use App\CoreBundle\Security\JWT\JWTManagerInterface;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTAuthenticator extends AbstractGuardAuthenticator
{
    const KEY_ACCESS_TOKEN = 'accessToken';
    const KEY_ACCESS_TOKEN_HEADER = 'X-AccessToken';

    /**
     * @var JWTManagerInterface
     */
    private $jwtManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param JWTManagerInterface $jwtManager
     * @param UserManager $userManager
     */
    public function __construct(JWTManagerInterface $jwtManager, UserManager $userManager)
    {
        $this->jwtManager  = $jwtManager;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {
        if ($request->request->has(self::KEY_ACCESS_TOKEN)) {
            $accessToken = $request->request->get(self::KEY_ACCESS_TOKEN);
        } elseif ($request->query->has(self::KEY_ACCESS_TOKEN)) {
            $accessToken = $request->query->get(self::KEY_ACCESS_TOKEN);
        } elseif ($request->headers->has(self::KEY_ACCESS_TOKEN_HEADER)) {
            $accessToken = $request->headers->get(self::KEY_ACCESS_TOKEN_HEADER);
        } else {
            return;
        }

        return $accessToken;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $payload = $this->jwtManager->parse($credentials);
        if (empty($payload['id'])) {
            return null;
        }
        return $this->userManager->findUserBy(['id' => $payload['id']]);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException(
            strtr($exception->getMessageKey(), $exception->getMessageData()),
            $exception
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @throws UnauthorizedHttpException
     * @return void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new UnauthorizedHttpException(null, 'A valid access token is required');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}

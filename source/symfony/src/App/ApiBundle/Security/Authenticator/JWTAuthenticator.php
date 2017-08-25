<?php

namespace App\ApiBundle\Security\Authenticator;

use App\CoreBundle\Repository\UserRepository;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
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
    const KEY_ACCESS_TOKEN_HEADER = 'Token';

    /**
     * @var JWTManagerInterface
     */
    private $jwtManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param JWTManagerInterface $jwtManager
     * @param UserRepository $userRepository
     */
    public function __construct(JWTManagerInterface $jwtManager, UserRepository $userRepository = null)
    {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
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
            $accessToken = preg_replace('/^Bearer: /', '', $request->headers->get(self::KEY_ACCESS_TOKEN_HEADER));
        } else {
            return null;
        }

        $payload = $this->jwtManager->parse($accessToken);

        if (empty($payload['id']) || empty($payload['token']) || empty($payload['verifier'])) {
            return null;
        }

        return $payload;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userRepository->find($credentials['id']);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!hash_equals($user->getToken(), $credentials['token'])) {
            return false;
        }
        if ($user->getJwtVerifier() > $credentials['verifier']) {
            return false;
        }

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

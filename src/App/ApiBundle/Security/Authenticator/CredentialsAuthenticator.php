<?php

namespace App\ApiBundle\Security\Authenticator;

use FOS\UserBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class CredentialsAuthenticator extends AbstractGuardAuthenticator
{
    const ROUTE_NAME = 'api_auth_authenticate';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserManager $userManager
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserManager $userManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userManager     = $userManager;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {
        if($request->get('_route') !== self::ROUTE_NAME) {
            return null;
        }

        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if (empty($username) || empty($password)) {
            return null;
        }

        return [
            'username' => $username,
            'password' => $password
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userManager->findUserByUsername($credentials['username']);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
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
        return null;
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

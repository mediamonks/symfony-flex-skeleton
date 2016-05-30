<?php

namespace App\ApiBundle\Security;

use App\CoreBundle\Security\TokenManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var TokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var EntityManager
     */
    private $entityManager; // @todo switch to user repository here

    /**
     * @param TokenManagerInterface $tokenManager
     * @param EntityManager $entityManager
     */
    public function __construct(TokenManagerInterface $tokenManager, EntityManager $entityManager)
    {
        $this->tokenManager = $tokenManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {
        if($request->request->has(self::KEY_ACCESS_TOKEN)) {
            $accessToken = $request->request->get(self::KEY_ACCESS_TOKEN);
        }
        elseif($request->query->has(self::KEY_ACCESS_TOKEN)) {
            $accessToken = $request->query->get(self::KEY_ACCESS_TOKEN);
        }
        elseif($request->headers->has(self::KEY_ACCESS_TOKEN_HEADER)) {
            $accessToken = $request->headers->get(self::KEY_ACCESS_TOKEN_HEADER);
        }
        else {
            return null;
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
        // @todo extract token
        return $this->entityManager->getRepository('AppCoreBundle:User')->findOneByUsername('root');
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
        // the exception is picked up by the rest api bundle so we don't have to do anything in here
        return null;
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
        // @todo throw exception so rest api bundle will pick it up?
        throw new UnauthorizedHttpException('A valid access token is required');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}

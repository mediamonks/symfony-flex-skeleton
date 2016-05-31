<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Sensio\Route("security", service="api.controller.security")
 */
class SecurityController
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var JWTManagerInterface
     */
    protected $jwtManager;

    /**
     * @param TokenStorage $tokenStorage
     * @param JWTManagerInterface $jwtManager
     */
    public function __construct(TokenStorage $tokenStorage, JWTManagerInterface $jwtManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtManager   = $jwtManager;
    }

    /**
     * @Sensio\Route("/login", name="api_security_login")
     *
     */
    public function loginAction()
    {
        return $this->jwtManager->sign([
            'id' => $this->tokenStorage->getToken()->getUser()->getId()
        ]);
    }

    /**
     * @Sensio\Route("/logout", name="api_security_logout")
     * @Sensio\Method({"DELETE"})
     */
    public function logoutAction()
    {
        // @todo invalidate all previous tokens by changing the token verifier
    }
}
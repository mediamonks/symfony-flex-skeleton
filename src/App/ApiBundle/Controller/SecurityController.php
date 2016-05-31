<?php

namespace App\ApiBundle\Controller;

use App\CoreBundle\Entity\User;
use App\CoreBundle\Security\JWT\JWTManagerInterface;
use Doctrine\ORM\EntityManager;
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
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param TokenStorage $tokenStorage
     * @param JWTManagerInterface $jwtManager
     * @param EntityManager $entityManager
     */
    public function __construct(TokenStorage $tokenStorage, JWTManagerInterface $jwtManager, EntityManager $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtManager   = $jwtManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @Sensio\Route("/login", name="api_security_login")
     * @Sensio\Method({"POST"})
     */
    public function loginAction()
    {
        $user = $this->getCurrentUser();

        return $this->jwtManager->sign([
            'id' => $user->getId(),
            'token' => $user->getToken(),
            'verifier' => $user->getJwtVerifier(),
            'username' => $user->getUsername()
        ]);
    }

    /**
     * @Sensio\Route("/logout", name="api_security_logout")
     * @Sensio\Method({"DELETE"})
     */
    public function logoutAction()
    {
        $user = $this->getCurrentUser();
        $user->updateJwtVerifier();
        $this->entityManager->flush($user);
    }

    /**
     * @return User
     */
    protected function getCurrentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}

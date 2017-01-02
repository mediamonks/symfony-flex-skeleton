<?php

namespace App\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Templating\EngineInterface;

/**
 * @Sensio\Route(service="admin.controller.security")
 */
class SecurityController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param EngineInterface $templateEngine
     */
    public function __construct(AuthenticationUtils $authenticationUtils, EngineInterface $templateEngine)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->templateEngine = $templateEngine;
    }

    /**
     * @Sensio\Route("/login", name="admin_login")
     */
    public function loginAction()
    {
        return new Response($this->templateEngine->render(
            'AppAdminBundle:security:login.html.twig',
            [
                'last_username' => $this->authenticationUtils->getLastUsername(),
                'error'         => $this->authenticationUtils->getLastAuthenticationError(),
            ]
        ));
    }

    /**
     * @Sensio\Route("/logout", name="admin_logout")
     */
    public function logoutAction()
    {
    }
}

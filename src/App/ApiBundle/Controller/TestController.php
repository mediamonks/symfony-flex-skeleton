<?php

namespace App\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @Sensio\Route("test")
 */
class TestController extends Controller
{
    /**
     * @Sensio\Route("")
     */
    public function testAction()
    {
        return 'Non Auth';
    }

    /**
     * @Sensio\Route("/me")
     */
    public function authAction()
    {
        return $this->get('security.context')->getToken()->getUser()->getId();
    }
}
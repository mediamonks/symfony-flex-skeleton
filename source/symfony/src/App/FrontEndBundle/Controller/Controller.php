<?php

namespace App\FrontEndBundle\Controller;

use App\CoreBundle\Controller\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param $name
     * @return mixed
     */
    protected function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    /**
     * @return mixed
     */
    protected function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }
}

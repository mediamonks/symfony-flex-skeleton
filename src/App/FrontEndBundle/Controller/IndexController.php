<?php

namespace App\FrontEndBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @author Robert Slootjes <robert@mediamonks.com>
 */
class IndexController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/", name="front_end_index")
     * @Sensio\Cache(smaxage=3600, maxage=3600)
     */
    public function indexAction()
    {
        return $this->render('AppFrontEndBundle:Home:index.html.twig');
    }
}

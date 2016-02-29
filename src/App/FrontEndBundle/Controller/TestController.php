<?php

namespace App\FrontEndBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;

/**
 * @author Robert Slootjes <robert@mediamonks.com>
 */
class TestController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/test", name="front_end_test")
     * @Sensio\Cache(smaxage=3600, maxage=3600)
     */
    public function indexAction()
    {
        foreach($this->getDoctrine()->getManager()->getRepository('AppCoreBundle:User')->findAll() as $user) {
            echo '<br>';
            echo '-------' . '<br>';
            echo $user->getEmail() . '<br>';
            echo $user->getEmailCanonical() . '<br>';

        }
        die;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Sensio\Route(path="/test/update", name="front_end_test_2")
     * @Sensio\Cache(smaxage=3600, maxage=3600)
     */
    public function updateAction()
    {
        $user = $this->getDoctrine()->getManager()->getRepository('AppCoreBundle:User')->findOneByUsername('root');
        //$user->setEmail(time() . '@mediamonks.com');
        $user->setLastLogin(new \DateTime);

        $this->getDoctrine()->getManager()->flush();

        echo $user->getEmail();
        die;
    }
}

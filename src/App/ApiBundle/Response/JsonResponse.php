<?php

namespace App\ApiBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    /**
     * We need this because setData() does json encoding already and
     * this messes up the jsonp callback.
     * It is a performance hit to let is decode/encode a second time
     *
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->data = $this->content = $content;

        return $this;
    }

    public function __toString()
    {
        //STUPID! FOR PRODUCTION IT'S BETTER TO TURN OFF LOGGER GUARD AUTHENTICATION LOGGER

        $content = $this->content;

        $this->setData($content);

        $ret = sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)."\r\n".
            $this->headers."\r\n".
            $this->getContent();

        $this->setContent($content);

        return $ret;
    }
}
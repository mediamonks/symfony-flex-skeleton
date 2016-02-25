<?php

namespace App\ApiBundle\Response;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * Sets the response content.
     *
     * @param mixed $content
     * @return Response
     * @api
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
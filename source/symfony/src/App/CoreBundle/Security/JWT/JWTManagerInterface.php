<?php

namespace App\CoreBundle\Security\JWT;

interface JWTManagerInterface
{
    /**
     * @param array $payload
     * @return string
     */
    public function sign(array $payload = []);

    /**
     * @param $token
     * @return array
     */
    public function parse($token);
}

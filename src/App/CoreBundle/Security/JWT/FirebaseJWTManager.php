<?php

namespace App\CoreBundle\Security\JWT;

use \Firebase\JWT\JWT;

class FirebaseJWTManager implements JWTManagerInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @param $key
     */
    public function __construct($key, $algorithm = 'HS256')
    {
        $this->key       = $key;
        $this->algorithm = $algorithm;
    }

    /**
     * @param array $payload
     * @return string
     */
    public function sign(array $payload = [])
    {
        return JWT::encode($payload, $this->key, $this->algorithm);
    }

    /**
     * @param $token
     * @return object
     */
    public function parse($token)
    {
        return (array) JWT::decode($token, $this->key, [$this->algorithm]);
    }
}

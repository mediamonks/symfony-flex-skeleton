<?php

namespace App\CoreBundle\Security\JWT;

use \Firebase\JWT\JWT;

class FirebaseJWTManager implements JWTManagerInterface
{
    const DEFAULT_ALGORITHM = 'HS256';

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
     * @param string $algorithm
     */
    public function __construct($key, $algorithm = self::DEFAULT_ALGORITHM)
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
     * @return array
     */
    public function parse($token)
    {
        return (array) JWT::decode($token, $this->key, [$this->algorithm]);
    }
}

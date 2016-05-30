<?php

namespace App\CoreBundle\Security;

class JWTManager implements TokenManagerInterface
{
    public function create()
    {
        return '';
    }

    public function parse()
    {
        return [];
    }

    public function validate()
    {
        return true;
    }

}
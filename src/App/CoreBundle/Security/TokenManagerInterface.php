<?php

namespace App\CoreBundle\Security;

interface TokenManagerInterface
{
    public function create();

    public function parse();

    public function validate();
}
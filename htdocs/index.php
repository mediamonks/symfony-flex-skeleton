<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/autoload.php';

Request::enableHttpMethodParameterOverride();

$kernel = new AppKernel(Environment::getName(), Environment::getDebug());
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);

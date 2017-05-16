<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../app/autoload.php';

// allow a PUT request to be sent as POST request with a PUT parameter so Symfony will handle it as PUT request
//Request::enableHttpMethodParameterOverride();

$kernel = new AppKernel(Environment::getName(), Environment::getDebug());

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);

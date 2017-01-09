<?php

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Debug\Debug;

date_default_timezone_set('UTC');

require_once __DIR__.'/../var/bootstrap.php.cache';

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__ . '/../vendor/autoload.php';

if (Environment::getDebug()) {
    Debug::enable();
}

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;

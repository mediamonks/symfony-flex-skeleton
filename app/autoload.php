<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

require_once __DIR__.'/../var/bootstrap.php.cache';

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/environment.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;

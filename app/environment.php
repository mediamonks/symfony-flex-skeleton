<?php

define('ENV_TEST', 'test'); // phpunit / behat
define('ENV_LOCAL', 'local');
define('ENV_DEVELOPMENT', 'development');
define('ENV_TESTING', 'testing');
define('ENV_ACCEPTANCE', 'acceptance');
define('ENV_PRODUCTION', 'production');

if (!defined('ENVIRONMENT')) {
    if (($environment = getenv('ENVIRONMENT')) == false || getenv('ENVIRONMENT') == ENV_PRODUCTION) {
        if (php_sapi_name() == 'cli') {
            if (strpos(__DIR__, 'C:') !== false) {
                $environment = ENV_LOCAL;
            } elseif (strpos(__DIR__, '/Users/') !== false) {
                $environment = ENV_LOCAL;
            } else {
                $environment = ENV_LOCAL;
            }
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            switch (@$_SERVER['SERVER_NAME']) {
                case 'localhost':
                case '127.0.0.1': {
                    $environment = ENV_LOCAL;
                    break;
                }
                case 'test': {
                    $environment = ENV_TEST;
                    break;
                }
                default: {
                    $environment = ENV_PRODUCTION;
                    break;
                }
            }
        } else {
            $environment = ENV_PRODUCTION;
        }
    }
    define('ENVIRONMENT', $environment);
}
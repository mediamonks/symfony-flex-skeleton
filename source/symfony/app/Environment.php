<?php

use Symfony\Component\Console\Input\ArgvInput;

class Environment
{
    const ENV_TEST = 'test'; // phpunit / behat
    const ENV_LOCAL = 'local';
    const ENV_DEVELOPMENT = 'development';
    const ENV_TESTING = 'testing';
    const ENV_ACCEPTANCE = 'acceptance';
    const ENV_PRODUCTION = 'production';

    const ENV_LEGACY_UAT = 'uat';

    const ENVIRONMENT_NAME_ENVIRONMENT = 'ENVIRONMENT'; // PHP_ENV
    const ENVIRONMENT_NAME_DEBUG = 'debug';

    const ARGUMENT_DEBUG = '--debug';
    const ARGUMENT_NO_DEBUG = '--no-debug';
    const ARGUMENT_ENVIRONMENT = '--env';
    const ARGUMENT_ENVIRONMENT_SHORT = '-e';

    const CLI_PATH_WINDOWS = 'AppData\Local';
    const CLI_PATH_MAC = '/Users/';

    private static $serverNamesLocal = ['localhost', 'local'];

    /**
     * @var string
     */
    private static $name;

    /**
     * @var bool
     */
    private static $debug;

    /**
     * @return array
     */
    public static function getNames()
    {
        return [
            self::ENV_TEST,
            self::ENV_LOCAL,
            self::ENV_DEVELOPMENT,
            self::ENV_TESTING,
            self::ENV_ACCEPTANCE,
            self::ENV_PRODUCTION,
        ];
    }

    /**
     * @param $environment
     * @return bool
     */
    public static function isValidName($environment)
    {
        return in_array($environment, self::getNames());
    }

    /**
     * @param $environment
     * @throws Exception
     */
    public static function assertIsValidName($environment)
    {
        if (!self::isValidName($environment)) {
            throw new \Exception(sprintf('Invalid environment "%s" was detected', $environment));
        }
    }

    /**
     * @return string
     */
    public static function getName()
    {
        if (empty(self::$name)) {
            $name = getenv(self::ENVIRONMENT_NAME_ENVIRONMENT);
            if (empty($name)) {
                if (self::isCli()) {
                    $name = self::getNameFromCli();
                } elseif (self::isWeb()) {
                    $name = self::getNameFromWeb();
                } else {
                    $name = self::ENV_PRODUCTION;
                }
            }

            $name = self::correctLegacyName($name);

            self::assertIsValidName($name);

            self::$name = $name;
        }

        return self::$name;
    }

    /**
     * @return bool
     */
    public static function getDebug()
    {
        if (is_null(self::$debug)) {

            self::$debug = false;
            if (self::getName() !== self::ENV_PRODUCTION) {
                self::$debug = true;
            }
            if (getenv(self::ENVIRONMENT_NAME_DEBUG)) {
                self::$debug = (bool)getenv(self::ENVIRONMENT_NAME_DEBUG);
            }
            if (self::isCli()) {
                $input = new ArgvInput();
                if (self::getName() !== self::ENV_PRODUCTION) {
                    self::$debug = !$input->hasParameterOption([self::ARGUMENT_NO_DEBUG]);
                } else {
                    self::$debug = $input->hasParameterOption([self::ARGUMENT_DEBUG]);
                }
            }
        }

        return self::$debug;
    }

    /**
     * @return bool
     */
    public static function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * @return string
     */
    public static function getNameFromCli()
    {
        $input = new ArgvInput();
        $name = $input->getParameterOption([self::ARGUMENT_ENVIRONMENT, self::ARGUMENT_ENVIRONMENT_SHORT]);
        if (!empty($name)) {
            return $name;
        }

        if (strpos(__DIR__, self::CLI_PATH_MAC) !== false) {
            return self::ENV_LOCAL;
        }
        if (strpos(getenv('LOCALAPPDATA'), self::CLI_PATH_WINDOWS) !== false) {
            return self::ENV_LOCAL;
        }

        return self::ENV_PRODUCTION;
    }

    /**
     * @return bool
     */
    public static function isWeb()
    {
        return !empty(self::getServerName());
    }

    /**
     * @return string
     */
    public static function getNameFromWeb()
    {
        if (in_array(self::getServerName(), self::$serverNamesLocal)) {
            return self::ENV_LOCAL;
        }

        return self::ENV_PRODUCTION;
    }

    /**
     * @return mixed
     */
    private static function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * @param $name
     * @return mixed
     */
    private static function correctLegacyName($name)
    {
        if ($name === self::ENV_LEGACY_UAT) {
            return self::ENV_TESTING;
        }

        return $name;
    }
}

<?php

namespace Installer\EventHandler;

use Zend\Text\Figlet\Figlet;

class WelcomeHandler extends AbstractHandler
{
    public function execute()
    {
        $figlet = $this->getFiglet();
        $this->write(rtrim($figlet->render('mediamonks')));
        $this->write($figlet->render('symfony skeleton'));

        $this->write('');

        $this->writeHeader('MediaMonks Symfony Skeleton Interactive Installer');
    }

    /**
     * @return Figlet
     */
    protected function getFiglet()
    {
        // composer install did not run yet so we can't use the regular autoloader

        $basePath = __DIR__ . '/../Resources/vendor/';
        $basePathZendText = $basePath . 'zendframework/zend-text/src/';
        $basePathZendStdlib = $basePath . 'zendframework/zend-stdlib/src/';

        require_once $basePathZendText . 'Figlet/Figlet.php';
        require_once $basePathZendStdlib . 'ArrayUtils.php';
        require_once $basePathZendStdlib . 'StringUtils.php';
        require_once $basePathZendStdlib . 'StringWrapper/StringWrapperInterface.php';
        require_once $basePathZendStdlib . 'StringWrapper/AbstractStringWrapper.php';
        require_once $basePathZendStdlib . 'StringWrapper/Iconv.php';
        require_once $basePathZendStdlib . 'StringWrapper/Intl.php';
        require_once $basePathZendStdlib . 'StringWrapper/MbString.php';
        require_once $basePathZendStdlib . 'StringWrapper/Native.php';

        return $figlet = new Figlet(['font' => __DIR__ . '/../Resources/fonts/small.flf']);
    }
}

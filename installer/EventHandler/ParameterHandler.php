<?php

namespace Installer\EventHandler;

use Zend\Math\Rand;

class ParameterHandler extends AbstractHandler
{
    const CHAR_LIST_URL_SAFE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const LENGTH_SECRET = 40;
    const LENGTH_ADMIN_DIRECTORY = 16;
    const LENGTH_ENCRYPTION_KEY = 64;

    public function execute()
    {
        $this->writeHeader('Parameters');

        $this->setSecret();
        $this->setAdminDirectory();
        $this->setEncryptionKey();

        $this->writeEmpty();
    }

    protected function setSecret()
    {
        $this->writeGenerating('secret');
        $this->replaceParameter('secret', Rand::getString(self::LENGTH_SECRET));
        $this->writeEmpty();
    }

    protected function setAdminDirectory()
    {
        $this->writeGenerating('admin directory');
        $this->replaceParameter('admin_directory', Rand::getString(
            self::LENGTH_ADMIN_DIRECTORY, self::CHAR_LIST_URL_SAFE));
        $this->writeEmpty();
    }

    protected function setEncryptionKey()
    {
        $this->writeGenerating('encryption key');
        $this->replaceParameter('encryption_key', Rand::getString(self::LENGTH_ENCRYPTION_KEY));
        $this->writeEmpty();
    }

    /**
     * @param $name
     */
    protected function writeGenerating($name)
    {
        $this->write(sprintf('Generating <comment>%s</comment>', $name));
    }
}

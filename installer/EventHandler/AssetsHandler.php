<?php

namespace Installer\EventHandler;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler;

class AssetsHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Assets');

        ScriptHandler::installAssets($this->event);

        $this->writeEmpty();
    }
}

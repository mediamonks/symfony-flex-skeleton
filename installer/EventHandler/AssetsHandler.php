<?php

namespace Installer\EventHandler;

use Composer\Script\CommandEvent;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler;

class AssetsHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Assets');

        try {
            // Symfony requires the deprecated CommandEvent instead of the regular Event
            $event = new CommandEvent(
                $this->event->getName(),
                $this->event->getComposer(),
                $this->event->getIO(),
                $this->event->isDevMode(),
                $this->event->getArguments(),
                $this->event->getFlags()
            );
            ScriptHandler::installAssets($event);
        } catch (\Exception $e) {
            $this->writeError('Could not install assets: ' . $e->getMessage());
            $this->writeEmpty();
        }

        $this->writeEmpty();
    }
}

<?php

namespace Installer\EventHandler;

use Composer\Script\CommandEvent;

interface HandlerInterface
{
    public function setEvent(CommandEvent $event);

    public function execute();
}

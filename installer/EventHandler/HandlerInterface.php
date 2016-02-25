<?php

namespace Installer\EventHandler;

use Composer\Script\Event;

interface HandlerInterface
{
    public function setEvent(Event $event);

    public function execute();
}

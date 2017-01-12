<?php

namespace Installer;

use Composer\Script\Event;

/**
 * @author Robert Slootjes <robert@mediamonks.com>
 *
 * These scripts are placed here so Symfony doesn't break when no local database
 */
class ScriptsDummy
{
    /**
     * @param Event $event
     */
    public static function clearCache(Event $event) {}

    /**
     * @param Event $event
     */
    public static function installAssets(Event $event) {}

}

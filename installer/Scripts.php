<?php

namespace Installer;

use Composer\Script\CommandEvent;
use Installer\EventHandler as Handler;

/**
 * @author Robert Slootjes <robert@mediamonks.com>
 */
class Scripts
{
    /**
     * @param CommandEvent $event
     */
    public static function postRootPackageInstall(CommandEvent $event)
    {
        $event->getIO()->write('');

        self::executeHandler($event, new Handler\WelcomeHandler());
        self::executeHandler($event, new Handler\DistFileHandler());
        self::executeHandler($event, new Handler\VersionHandler());

        $event->getIO()->write('');
        $event->getIO()->write('Installing dependencies');
        $event->getIO()->write('=======================');
        $event->getIO()->write('');
    }

    /**
     * @param CommandEvent $event
     */
    public static function postCreateProjectCmd(CommandEvent $event)
    {
        $event->getIO()->write('');

        self::executeHandler($event, new Handler\RequirementsHandler());
        self::executeHandler($event, new Handler\SecurityHandler());
        self::executeHandler($event, new Handler\ProjectHandler());
        self::executeHandler($event, new Handler\AssemblaHandler());
        self::executeHandler($event, new Handler\ParameterHandler());
        self::executeHandler($event, new Handler\DatabaseHandler());
        self::executeHandler($event, new Handler\UserHandler());
        self::executeHandler($event, new Handler\AssetsHandler());
        self::executeHandler($event, new Handler\CleanHandler());

        $message = 'MediaMonks Symfony Skeleton was successfully installed, happy coding!';

        $event->getIO()->write('');
        $event->getIO()->write(str_repeat('=', strlen($message)));
        $event->getIO()->write($message);
        $event->getIO()->write(str_repeat('=', strlen($message)));
        $event->getIO()->write('');
        $event->getIO()->write('');
    }

    /**
     * @param CommandEvent $event
     * @param Handler\HandlerInterface $handler
     */
    protected static function executeHandler(CommandEvent $event, Handler\HandlerInterface $handler)
    {
        $handler->setEvent($event)->execute();
    }
}

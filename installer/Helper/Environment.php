<?php

namespace Installer\Helper;

class Environment
{
    /**
     * @return bool
     */
    public static function isSkeletonDevMode()
    {
        return file_exists(File::getFullPath(File::DEV));
    }
}
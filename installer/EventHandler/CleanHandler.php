<?php

namespace Installer\EventHandler;

use Installer\Helper\Environment;
use Installer\Helper\File;

class CleanHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Cleanup');

        if(Environment::isSkeletonDevMode()) {
            return;
        }

        File::removeLinesFromFileByContent(File::GITIGNORE, [
            File::META,
            File::DEV,
            File::README,
        ]);

        $this->io->write('Removing Dist Files');
        File::remove(File::README_DIST);
        File::remove(File::PARAMETERS_DIST);

        $this->io->write('Removing ComposerBundle');
        File::remove(File::INSTALLER);

        $this->io->write('');
    }
}

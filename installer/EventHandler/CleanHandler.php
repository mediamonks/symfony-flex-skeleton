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

        // make sure after installation the regular cache clear from symfony will be used after installing a package
        File::replaceInFile(
            'Installer\\ScriptsDummy',
            'Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler',
            File::COMPOSER_JSON
        );

        // remove files which identify the as being a development clone
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

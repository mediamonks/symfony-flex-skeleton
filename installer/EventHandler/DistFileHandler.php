<?php

namespace Installer\EventHandler;

use Installer\Helper\File;

class DistFileHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Preparing');

        $this->copy(File::README_DIST, File::README);
        $this->copy(File::PARAMETERS_DIST, File::PARAMETERS);

        $this->writeEmpty();
    }

    protected function copy($source, $destination)
    {
        $this->write(sprintf('Copying <comment>%s</comment> to <comment>%s</comment>', $source, $destination));

        File::copy($source, $destination);

        $this->writeEmpty();
    }
}

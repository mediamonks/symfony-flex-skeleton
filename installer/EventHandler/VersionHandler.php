<?php

namespace Installer\EventHandler;

use Installer\Helper\File;

class VersionHandler extends AbstractHandler
{
    const FILE_GIT_ORIG_HEAD = '.git/ORIG_HEAD';
    const GIT_COMMAND_VERSION = 'git rev-parse HEAD';
    const BUILD_WRAPPER = '{build_%s}';
    const REVISION_UNKNOWN = 'unknown';

    public function execute()
    {
        $this->writeHeader('Version');

        $this->write('Reading package version details from <comment>git</comment>');
        $this->writeEmpty();

        $package = $this->event->getComposer()->getPackage();

        if($package->getStability() !== 'stable') {
            $question = sprintf('The stability of this release is <error>%s</error> instead of <comment>stable</comment>. Are you sure you want to continue? (<comment>n</comment>): ', $package->getStability());
            if(!$this->io->askConfirmation($question, false)) {
                $this->writeError('Aborting installation');
                exit;
            }
            $this->io->write('');
        }

        // try to get current revision
        $revision = trim(exec(self::GIT_COMMAND_VERSION));
        if (empty($revision) && file_exists(File::getFullPath(self::FILE_GIT_ORIG_HEAD))) {
            $revision = trim(file_get_contents(File::getFullPath(self::FILE_GIT_ORIG_HEAD)));
        }
        if (empty($revision)) {
            $revision = self::REVISION_UNKNOWN;
        }

        $data = [
            'created'   => (new \DateTime)->format(\DateTime::RFC3339),
            'name'      => $package->getPrettyName(),
            'version'   => $package->getVersion(),
            'revision'  => $revision,
            'stability' => $package->getStability()
        ];

        // put in meta data file so it can be used in other scripts
        $this->write(sprintf('Writing package version details to <comment>%s</comment>', File::META));
        File::writeMetaData('package', $data);

        // put in readme so it's clearly visible in Assembla or other tools
        $this->write(sprintf('Writing package version details to <comment>%s</comment>', File::README));

        File::replaceInFile(
            array_map(function($value) { return sprintf(self::BUILD_WRAPPER, $value); }, array_keys($data)),
            array_values($data),
            File::README
        );
    }
}

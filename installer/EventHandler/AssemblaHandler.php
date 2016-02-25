<?php

namespace Installer\EventHandler;

use Installer\Helper\File;

class AssemblaHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Assembla');

        if($this->askConfirmation('Is there an Assembla space for this project? ')) {
            $url = $this->askAndValidate('Please enter your Assembla space url: ', function($value) {
                if(!preg_match('~^https://www\.assembla\.com/spaces/.+/$~', $value)) {
                    throw new \Exception('Assembla space url should look like https://www.assembla.com/spaces/<project_space_name>/');
                }
                return $value;
            });

            $urlData = explode('/', rtrim($url, '/'));
            $name = end($urlData);

            $this->writeEmpty();
            $this->write(sprintf('Using <comment>%s</comment> as Assembla space url', $url));
            $this->write(sprintf('Using <comment>%s</comment> as Assembla space name', $name));
            $this->writeEmpty();

            // put in meta data file so it can be used in other scripts
            $this->write(sprintf('Writing package version details to <comment>%s</comment>', File::META));
            File::writeMetaData('assembla', ['name' => $name, 'url' => $url]);

            $this->write(sprintf('Writing Assembla details to <comment>%s</comment>', File::README));

            File::replaceInFile(
                ['{assembla_space_name}', '{assembla_space_url}'],
                [$name, $url],
                File::README
            );
        }
        else {
            $this->write(sprintf('Removing Assembla details from <comment>%s</comment>', File::README));

            File::removeLinesFromFile(File::getFullPath(File::README), range(3, 7));
        }

        $this->writeEmpty();
    }


}

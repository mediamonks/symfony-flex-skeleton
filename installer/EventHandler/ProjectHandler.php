<?php

namespace Installer\EventHandler;

use Installer\Helper\File;

class ProjectHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Project');

        $brandName = $this->askAndValidate('Please enter the name of the brand: ', function ($value) {
            if (empty(trim($value))) {
                throw new \Exception('The brand name can not be empty');
            }
            return $value;
        });

        $projectName = $this->askAndValidate('Please enter the name of the project: ', function ($value) {
            if (empty(trim($value))) {
                throw new \Exception('The project name can not be empty');
            }
            return $value;
        });

        // put in meta data so it can be used in other scripts
        $this->write(sprintf('Writing project details to <comment>%s</comment>', File::META));
        File::writeMetaData('brand', [
            'name' => $brandName,
            'sanatized' => $this->sanatize($brandName)
        ]);
        File::writeMetaData('project', [
            'name' => $projectName,
            'sanatized' => $this->sanatize($projectName)
        ]);

        $this->write(sprintf('Writing project details to <comment>%s</comment>', File::README));
        File::replaceInFile(
            ['{brand_name}', '{brand_name_line}', '{project_name}', '{project_name_line}'],
            [$brandName, str_repeat('=', strlen($brandName)), $projectName, str_repeat('=', strlen($projectName))],
            File::README
        );

        $this->writeEmpty();
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function sanatize($value)
    {
        return str_replace(' ', '', preg_replace('/[^a-zA-Z0-9]+/', '', $value));
    }
}

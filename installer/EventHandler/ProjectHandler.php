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

        $this->writeEmpty();

        // replace page titles
        $this->write('Writing project title to templates');

        $titleCurrent = 'Skeleton';
        $titleNew = sprintf('%s %s', $brandName, $projectName);

        File::replaceInFile($titleCurrent, $titleNew, File::SONATA_ADMIN_CONFIG);
        File::replaceInFile($titleCurrent, $titleNew, File::FRONT_END_LAYOUT);

        $this->writeEmpty();

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
            ['{brand_name}', '{project_name}'],
            [$brandName, $projectName],
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

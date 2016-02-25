<?php

namespace Installer\EventHandler;

use Symfony\Component\Process\Process;

class RequirementsHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Requirements');

        $process = new Process('php bin/symfony_requirements');
        $process->run(function ($type, $buffer) {
            echo str_replace('web/config.php', 'var/config.php', $buffer);
        });

        $this->writeEmpty();

        if(!$this->askConfirmation('Did the requirement checker approve your system?')) {
            $this->writeError('Fix the issues and try again, aborting installation');
            exit;
        }

        $this->writeEmpty();
    }
}

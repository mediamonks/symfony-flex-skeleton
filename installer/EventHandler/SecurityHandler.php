<?php

namespace Installer\EventHandler;

use Symfony\Component\Process\Process;

class SecurityHandler extends AbstractHandler
{
    public function execute()
    {
        $this->writeHeader('Security');

        $process = new Process('php vendor/sensiolabs/security-checker/security-checker security:check');
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        $this->writeEmpty();

        if(!$this->askConfirmation('Did the security checker approve all used packages?')) {
            $this->writeError('Upgrade or remove the packages with issues and try again, aborting installation');
            exit;
        }

        $this->writeEmpty();
    }
}

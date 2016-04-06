<?php

namespace Installer\EventHandler;

use Installer\Helper\File;
use Composer\Script\CommandEvent;
use Composer\IO\IOInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var CommandEvent
     */
    protected $event;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @param CommandEvent $event
     * @return $this
     */
    public function setEvent(CommandEvent $event)
    {
        $this->event = $event;
        $this->io = $event->getIO();
        return $this;
    }

    /**
     * @param $message
     */
    protected function writeHeader($message)
    {
        $this->io->write($message);
        $this->io->write(str_repeat('=', strlen($message)));
        $this->io->write('');
    }

    /**
     * @param $question
     * @param null $default
     * @return mixed
     */
    protected function ask($question, $default = null)
    {
        return $this->io->ask(sprintf('<question>%s</question> (<comment>%s</comment>): ', $question, $default), $default);
    }

    /**
     * @param $question
     * @param bool $default
     * @return bool
     */
    protected function askConfirmation($question, $default = true)
    {
        $defaultString = 'y';
        if(!$default) {
            $defaultString = 'n';
        }
        return $this->io->askConfirmation(sprintf('<question>%s</question> (<comment>%s</comment>): ', $question, $defaultString), $default);
    }

    /**
     * @param $question
     * @param $validator
     * @param null $attempts
     * @param null $default
     * @return mixed
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null)
    {
        return $this->io->askAndValidate(sprintf('<question>%s</question> (<comment>%s</comment>): ', $question, $default), $validator, $attempts, $default);
    }

    /**
     * @param $message
     * @param bool $newline
     * @param $verbosity
     */
    public function writeError($message, $newline = true, $verbosity = IOInterface::NORMAL)
    {
        $this->io->write(sprintf('<error>%s</error>', $message), $newline, $verbosity);
    }

    /**
     * @param $message
     * @param bool $newline
     * @param $verbosity
     */
    protected function writeSuccess($message, $newline = true, $verbosity = IOInterface::NORMAL)
    {
        $this->io->write(sprintf('<info>%s</info>', $message), $newline, $verbosity);
    }

    /**
     * @param $message
     * @param bool $newline
     * @param $verbosity
     */
    protected function write($message, $newline = true, $verbosity = IOInterface::NORMAL)
    {
        $this->io->write($message, $newline, $verbosity);
    }

    /**
     *
     */
    public function writeEmpty()
    {
        $this->io->write('');
    }

    /**
     * @param $name
     * @param $value
     * @param string $file
     */
    protected function replaceParameter($name, $value, $file = File::PARAMETERS)
    {
        if(is_null($value)) {
            $value = 'null';
        }

        $this->write(sprintf(
            'Setting parameter <comment>%s</comment> to <comment>%s</comment> in <comment>%s</comment>',
            $name,
            $value,
            $file
        ));

        File::replaceParameterInFile($name, $value, $file);
    }

    /**
     * @param $command
     */
    protected function executePhpBinCommand($command)
    {
        exec(sprintf('php bin/ %s', $command));
    }

    /**
     * @param $command
     */
    protected function executeSymfonyCommand($command)
    {
        exec(sprintf('php bin/console %s', $command));
    }
}

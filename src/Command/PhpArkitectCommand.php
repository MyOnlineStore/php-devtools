<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('phparkitect', 'PHPArkitect')]
final class PhpArkitectCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        return new Process(
            [$this->withVendorBinPath('phparkitect'), 'check'],
            timeout: null,
        );
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getRootDir() . 'vendor/bin/phparkitect')) {
            return false;
        }

        return \is_file($configuration->getWorkingDir() . 'phparkitect.php');
    }
}

<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('psalm', 'Psalm')]
final class PsalmCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        $command = [
            $this->withVendorBinPath('psalm'),
            '--threads=' . $this->configuration->getThreads(),
        ];

        if ($this->isGitHubFormat($input)) {
            $command[] = '--output-format=github';
        }

        return new Process($command, timeout: null);
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getRootDir() . 'vendor/bin/psalm')) {
            return false;
        }

        return \is_file($configuration->getWorkingDir() . 'psalm.xml.dist')
            || \is_file($configuration->getWorkingDir() . 'psalm.xml');
    }
}

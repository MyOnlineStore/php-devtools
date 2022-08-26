<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('phpstan', 'PHPStan')]
final class PhpStanCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        $command = [
            $this->withVendorBinPath('phpstan'),
        ];

        if ($this->isGitHubFormat($input)) {
            $command[] = '--error-format=github';
        }

        return new Process($command, timeout: null);
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getRootDir() . 'vendor/bin/phpstan')) {
            return false;
        }

        return \is_file($configuration->getWorkingDir() . 'phpstan.neon.dist')
            || \is_file($configuration->getWorkingDir() . 'phpstan.neon');
    }
}

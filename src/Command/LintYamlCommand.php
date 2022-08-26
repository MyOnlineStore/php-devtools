<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('lint-yaml', 'Lint YAML')]
final class LintYamlCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        return new Process(
            [
                $this->withBinPath('console'),
                'lint:yaml',
                'config',
                '--parse-tags',
            ],
            timeout: null,
        );
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (
            !\is_file($configuration->getWorkingDir() . 'bin/console') ||
            !\is_dir($configuration->getWorkingDir() . 'config')
        ) {
            return false;
        }

        $process = new Process([$configuration->getWorkingDir() . 'bin/console', 'list']);
        $process->run();

        return \str_contains($process->getOutput(), 'lint:yaml');
    }
}

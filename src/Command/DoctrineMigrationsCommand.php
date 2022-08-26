<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('doctrine-migrations', 'Doctrine Migrations, always runs in test environment')]
final class DoctrineMigrationsCommand extends DevToolsCommand
{
    /** @inheritDoc */
    protected function getMultiProcess(InputInterface $input): array
    {
        return [
            // Ensure we're up-to-date
            new Process(
                [
                    $this->withBinPath('console'),
                    'doctrine:migrations:migrate',
                    '--allow-no-migration',
                    '--no-interaction',
                    '--env=test',
                ],
                timeout: null,
            ),
            // Test all down patches
            new Process(
                [
                    $this->withBinPath('console'),
                    'doctrine:migrations:migrate',
                    'first',
                    '--allow-no-migration',
                    '--no-interaction',
                    '--env=test',
                ],
                timeout: null,
            ),
            // Test all migrations ánd if down patches did their job
            new Process(
                [
                    $this->withBinPath('console'),
                    'doctrine:migrations:migrate',
                    '--allow-no-migration',
                    '--no-interaction',
                    '--env=test',
                ],
                timeout: null,
            ),
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getWorkingDir() . 'bin/console')) {
            return false;
        }

        $process = new Process([$configuration->getWorkingDir() . 'bin/console', 'list', '--env=test']);
        $process->run();

        return \str_contains($process->getOutput(), 'doctrine:migrations:migrate');
    }
}

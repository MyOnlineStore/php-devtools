<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Process\Process;

final class DoctrineMigrationsCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'doctrine-migrations';

    /** @var string|null */
    protected static $defaultDescription = 'Doctrine Migrations, always runs in test environment';

    /**
     * @inheritDoc
     */
    protected function getMultiCommand(): array
    {
        return [
            // Ensure we're up-to-date
            [
                $this->withBinPath('console'),
                'doctrine:migrations:migrate',
                '--allow-no-migration',
                '--no-interaction',
                '--env=test',
            ],
            // Test all down patches
            [
                $this->withBinPath('console'),
                'doctrine:migrations:migrate',
                'first',
                '--allow-no-migration',
                '--no-interaction',
                '--env=test',
            ],
            // Test all migrations ánd if down patches did their job
            [
                $this->withBinPath('console'),
                'doctrine:migrations:migrate',
                '--allow-no-migration',
                '--no-interaction',
                '--env=test',
            ],
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (
            !\is_file($configuration->getRootDir() . 'bin/console') ||
            !\is_dir($configuration->getRootDir() . 'config')
        ) {
            return false;
        }

        $process = new Process([$configuration->getRootDir() . 'bin/console', 'list', '--env=test']);
        $process->run();

        return \str_contains($process->getOutput(), 'doctrine:migrations:migrate');
    }
}

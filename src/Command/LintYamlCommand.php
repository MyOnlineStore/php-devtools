<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Process\Process;

final class LintYamlCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'lint-yaml';

    /** @var string|null */
    protected static $defaultDescription = 'Lint YAML';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withBinPath('console'),
            'lint:yaml',
            'config',
            '--parse-tags',
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

        $process = new Process([$configuration->getRootDir() . 'bin/console', 'list']);
        $process->run();

        return \str_contains($process->getOutput(), 'lint:yaml');
    }
}

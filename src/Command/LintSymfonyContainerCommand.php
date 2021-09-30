<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Process\Process;

final class LintSymfonyContainerCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'lint-container';

    /** @var string|null */
    protected static $defaultDescription = 'Lint Symfony container';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withBinPath('console'),
            'lint:container',
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getRootDir() . 'bin/console')) {
            return false;
        }

        $process = new Process([$configuration->getRootDir() . 'bin/console', 'list']);
        $process->run();

        return \str_contains($process->getOutput(), 'lint:container');
    }
}

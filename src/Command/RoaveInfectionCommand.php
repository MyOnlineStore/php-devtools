<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;

final class RoaveInfectionCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'infection';

    /** @var string|null */
    protected static $defaultDescription = 'Roave Infection';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withVendorBinPath('roave-infection-static-analysis-plugin'),
            '--only-covered',
            '--show-mutations',
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'vendor/bin/roave-infection-static-analysis-plugin')
            && (
                \is_file($configuration->getRootDir() . 'infection.json.dist') ||
                \is_file($configuration->getRootDir() . 'infection.json')
            );
    }
}

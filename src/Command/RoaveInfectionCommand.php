<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

final class RoaveInfectionCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'infection';

    /** @var string|null */
    protected static $defaultDescription = 'Roave Infection';

    protected function getProcess(InputInterface $input): Process
    {
        return new Process(
            [
                $this->withVendorBinPath('roave-infection-static-analysis-plugin'),
                '--only-covered',
                '--show-mutations',
            ],
            env: ['XDEBUG_MODE' => 'coverage'],
            timeout: null,
        );
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

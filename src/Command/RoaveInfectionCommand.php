<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('infection', 'Roave Infection')]
final class RoaveInfectionCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        $command = [
            $this->withVendorBinPath('roave-infection-static-analysis-plugin'),
            '--threads=' . $this->configuration->getThreads(),
            '--only-covered',
            '--show-mutations',
        ];

        if ($this->isGitHubFormat($input)) {
            $command[] = '--logger-github';
        }

        return new Process($command, env: ['XDEBUG_MODE' => 'coverage'], timeout: null);
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getRootDir() . 'vendor/bin/roave-infection-static-analysis-plugin')) {
            return false;
        }

        return \is_file($configuration->getWorkingDir() . 'infection.json.dist')
            || \is_file($configuration->getWorkingDir() . 'infection.json');
    }
}

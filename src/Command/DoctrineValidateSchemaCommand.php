<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

#[AsCommand('doctrine-validate-schema', 'Doctrine schema validation')]
final class DoctrineValidateSchemaCommand extends DevToolsCommand
{
    protected function getProcess(InputInterface $input): Process
    {
        return new Process(
            [
                $this->withBinPath('console'),
                'doctrine:schema:validate',
                '--skip-sync',
                '--no-interaction',
            ],
            timeout: null,
        );
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        if (!\is_file($configuration->getWorkingDir() . 'bin/console')) {
            return false;
        }

        $process = new Process([$configuration->getWorkingDir() . 'bin/console', 'list']);
        $process->run();

        return \str_contains($process->getOutput(), 'doctrine:schema:validate');
    }
}

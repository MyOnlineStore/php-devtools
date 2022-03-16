<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class DevToolsCommand extends Command
{
    protected Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = $this->getMultiCommand();

        if (0 === \count($commands)) {
            $commands[] = $this->getCommand();
        }

        $exitCode = 0;

        foreach ($commands as $command) {
            $process = new Process(
                \array_merge(
                    $command,
                    (array) ($input->getArguments()['args'] ?? [])
                ),
                null,
                null,
                null,
                null
            );
            $process->start();

            $exitCode |= $process->wait(
                static function (string $_type, string $buffer) use ($output): void {
                    $output->write($buffer);
                }
            );
        }

        return $exitCode;
    }

    protected function withBinPath(string $command): string
    {
        return $this->configuration->getRootDir() . 'bin/' . $command;
    }

    protected function withVendorBinPath(string $command): string
    {
        return $this->configuration->getRootDir() . 'vendor/bin/' . $command;
    }

    abstract public static function isAvailable(Configuration $configuration): bool;

    /**
     * @return list<string>
     */
    protected function getCommand(): array
    {
        return [];
    }

    /**
     * @return list<list<string>>
     */
    protected function getMultiCommand(): array
    {
        return [];
    }
}

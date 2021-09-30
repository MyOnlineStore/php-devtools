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
    /** @var Configuration */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = new Process(
            \array_merge(
                $this->getCommand(),
                (array) ($input->getArguments()['args'] ?? [])
            )
        );
        $process->start();

        return $process->wait(
            static function (string $_type, string $buffer) use ($output): void {
                $output->write($buffer);
            }
        );
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
    abstract protected function getCommand(): array;
}

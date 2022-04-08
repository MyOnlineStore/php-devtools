<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class DevToolsCommand extends Command
{
    public function __construct(
        protected Configuration $configuration,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'format',
            null,
            InputOption::VALUE_OPTIONAL,
            'Output format to use (by supported commands).'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $processes = $this->getMultiProcess($input);

        if (0 === \count($processes)) {
            $processes[] = $this->getProcess($input);
        }

        $exitCode = 0;

        foreach ($processes as $process) {
            $process->start();

            $exitCode |= $process->wait(
                static function (string $_type, string $buffer) use ($output): void {
                    $output->write($buffer);
                }
            );
        }

        return $exitCode;
    }

    protected function getProcess(InputInterface $input): Process
    {
        throw new \RuntimeException('Either implement getProcess() or getMultiProcess()');
    }

    /**
     * @return list<Process>
     */
    protected function getMultiProcess(InputInterface $input): array
    {
        return [];
    }

    abstract public static function isAvailable(Configuration $configuration): bool;

    protected function isGitHubFormat(InputInterface $input): bool
    {
        return 'github' === $input->getOption('format');
    }

    protected function withBinPath(string $command): string
    {
        return $this->configuration->getRootDir() . 'bin/' . $command;
    }

    protected function withVendorBinPath(string $command): string
    {
        return $this->configuration->getRootDir() . 'vendor/bin/' . $command;
    }
}

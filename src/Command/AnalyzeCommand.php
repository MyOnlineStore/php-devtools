<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class AnalyzeCommand extends Command
{
    /** @var string|null */
    protected static $defaultName = 'analyze';

    /** @var string|null */
    protected static $defaultDescription = 'Run all enabled tools.';

    public function __construct(
        private Configuration $configuration,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $enabledTools = $this->configuration->getEnabledTools();

        $runningProcesses = [];

        foreach ($enabledTools as $name => $_command) {
            $process = new Process([__DIR__ . '/../../bin/devtools', $name]);
            $process->start();

            $runningProcesses[] = $process;
        }

        $exitCode = 0;

        while (\count($runningProcesses)) {
            foreach ($runningProcesses as $i => $runningProcess) {
                // specific process is finished, so we remove it
                if (!$runningProcess->isRunning()) {
                    $exitCode |= (int) $runningProcess->getExitCode();

                    $output->write($runningProcess->getOutput());

                    unset($runningProcesses[$i]);
                }

                \usleep(500);
            }
        }

        return $exitCode;
    }
}

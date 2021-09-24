<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnalyzeCommand extends Command
{
    /** @var string|null */
    protected static $defaultName = 'analyze';

    /** @var string|null */
    protected static $defaultDescription = 'Run all enabled tools.';

    /** @var Configuration */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $application = $this->getApplication();
        \assert(null !== $application);

        foreach ($this->configuration->getEnabledTools() as $name => $_command) {
            $exitCode |= $application->find($name)->run($input, $output);
        }

        return $exitCode;
    }
}

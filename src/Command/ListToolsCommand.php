<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListToolsCommand extends Command
{
    /** @var string|null */
    protected static $defaultName = 'list:enabled-tools';

    /** @var string|null */
    protected static $defaultDescription = 'Lists enabled tools (in JSON).';

    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write(\json_encode(\array_keys($this->configuration->getEnabledTools()), JSON_THROW_ON_ERROR));

        return 0;
    }
}

<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools;

use MyOnlineStore\DevTools\Command\AnalyzeCommand;
use MyOnlineStore\DevTools\Command\CodesnifferCommand;
use MyOnlineStore\DevTools\Command\LintSymfonyContainerCommand;
use MyOnlineStore\DevTools\Command\ListPhpVersionsCommand;
use MyOnlineStore\DevTools\Command\ListToolsCommand;
use MyOnlineStore\DevTools\Command\PhpUnitCommand;
use MyOnlineStore\DevTools\Command\PsalmCommand;
use Symfony\Component\Console\Command\Command;

final class DevTools
{
    /** @var Configuration */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return list<Command>
     */
    public function getCommands(): array
    {
        return [
            new AnalyzeCommand($this->configuration),
            new CodesnifferCommand($this->configuration),
            new LintSymfonyContainerCommand($this->configuration),
            new ListToolsCommand($this->configuration),
            new ListPhpVersionsCommand($this->configuration),
            new PhpUnitCommand($this->configuration),
            new PsalmCommand($this->configuration),
        ];
    }
}

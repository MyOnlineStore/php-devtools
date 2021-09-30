<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Tests;

use MyOnlineStore\DevTools\Command\AnalyzeCommand;
use MyOnlineStore\DevTools\Command\CodesnifferCommand;
use MyOnlineStore\DevTools\Command\LintSymfonyContainerCommand;
use MyOnlineStore\DevTools\Command\LintYamlCommand;
use MyOnlineStore\DevTools\Command\ListPhpVersionsCommand;
use MyOnlineStore\DevTools\Command\ListToolsCommand;
use MyOnlineStore\DevTools\Command\PhpUnitCommand;
use MyOnlineStore\DevTools\Command\PsalmCommand;
use MyOnlineStore\DevTools\Command\RoaveInfectionCommand;
use MyOnlineStore\DevTools\Configuration;
use MyOnlineStore\DevTools\DevTools;
use PHPUnit\Framework\TestCase;

final class DevToolsTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    /** @var DevTools */
    private $devTools;

    protected function setUp(): void
    {
        $this->devTools = new DevTools(
            $this->configuration = new Configuration()
        );
    }

    public function testGetCommands(): void
    {
        self::assertEquals(
            [
                new AnalyzeCommand($this->configuration),
                new CodesnifferCommand($this->configuration),
                new LintSymfonyContainerCommand($this->configuration),
                new LintYamlCommand($this->configuration),
                new ListToolsCommand($this->configuration),
                new ListPhpVersionsCommand($this->configuration),
                new PhpUnitCommand($this->configuration),
                new PsalmCommand($this->configuration),
                new RoaveInfectionCommand($this->configuration),
            ],
            $this->devTools->getCommands()
        );
    }
}

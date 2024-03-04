<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Tests;

use MyOnlineStore\DevTools\Command\AnalyzeCommand;
use MyOnlineStore\DevTools\Command\CodesnifferCommand;
use MyOnlineStore\DevTools\Command\DoctrineMigrationsCommand;
use MyOnlineStore\DevTools\Command\DoctrineValidateSchemaCommand;
use MyOnlineStore\DevTools\Command\LintSymfonyContainerCommand;
use MyOnlineStore\DevTools\Command\LintYamlCommand;
use MyOnlineStore\DevTools\Command\ListPhpVersionsCommand;
use MyOnlineStore\DevTools\Command\ListToolsCommand;
use MyOnlineStore\DevTools\Command\PhpArkitectCommand;
use MyOnlineStore\DevTools\Command\PhpStanCommand;
use MyOnlineStore\DevTools\Command\PhpUnitCommand;
use MyOnlineStore\DevTools\Command\PsalmCommand;
use MyOnlineStore\DevTools\Configuration;
use MyOnlineStore\DevTools\DevTools;
use PHPUnit\Framework\TestCase;

final class DevToolsTest extends TestCase
{
    private Configuration $configuration;
    private DevTools $devTools;

    protected function setUp(): void
    {
        $this->devTools = new DevTools(
            $this->configuration = new Configuration(),
        );
    }

    public function testGetCommands(): void
    {
        self::assertEquals(
            [
                new AnalyzeCommand($this->configuration),
                new CodesnifferCommand($this->configuration),
                new DoctrineMigrationsCommand($this->configuration),
                new DoctrineValidateSchemaCommand($this->configuration),
                new LintSymfonyContainerCommand($this->configuration),
                new LintYamlCommand($this->configuration),
                new ListToolsCommand($this->configuration),
                new ListPhpVersionsCommand($this->configuration),
                new PhpArkitectCommand($this->configuration),
                new PhpStanCommand($this->configuration),
                new PhpUnitCommand($this->configuration),
                new PsalmCommand($this->configuration),
            ],
            $this->devTools->getCommands(),
        );
    }

    public function testListPhpVersion(): void
    {
        \chdir(\dirname(__DIR__));
        $phpVersions = \exec('./bin/devtools list:php-versions');

        self::assertIsString($phpVersions);
        self::assertStringContainsString(\PHP_MAJOR_VERSION . '.' . \PHP_MINOR_VERSION, $phpVersions);
    }
}

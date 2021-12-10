<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools;

use Composer\Semver\Semver;
use MyOnlineStore\DevTools\Command\DevToolsCommand;

final class Configuration
{
    private const PHP_VERSIONS = [
        '7.4',
        '8.0',
        '8.1',
    ];

    /** @var array<string, class-string<DevToolsCommand>>|null */
    private ?array $enabledTools;

    /** @var list<string>|null */
    private ?array $phpVersions;

    private string $rootDir;

    public function __construct()
    {
        $possibleRoots = [
            __DIR__ . '/../../../../',  // From vendor/myonlinestore/php-devtools/bin
            __DIR__ . '/../../',        // From vendor/bin
            __DIR__ . '/../',           // From bin
        ];

        foreach ($possibleRoots as $root) {
            if (\is_file($root . 'composer.json')) {
                $this->rootDir = $root;

                return;
            }
        }

        throw new \RuntimeException('Unable to determine project root');
    }

    /**
     * @return list<string>
     */
    private function gatherPhpVersions(): array
    {
        /** @var array<array-key, mixed> $composer */
        $composer = \json_decode(
            \file_get_contents($this->rootDir . 'composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!isset($composer['require']['php'])) {
            throw new \RuntimeException('Required PHP version not specified in composer.json');
        }

        $versions = [];

        foreach (self::PHP_VERSIONS as $version) {
            if (!Semver::satisfies($version, (string) $composer['require']['php'])) {
                continue;
            }

            $versions[] = $version;
        }

        return $versions;
    }

    /**
     * @return list<class-string<DevToolsCommand>>
     */
    private function gatherAvailableCommands(): array
    {
        $commands = [];

        foreach (\get_declared_classes() as $class) {
            if (!\is_subclass_of($class, DevToolsCommand::class)) {
                continue;
            }

            $commands[] = $class;
        }

        return $commands;
    }

    /**
     * @return array<string, class-string<DevToolsCommand>>
     */
    private function gatherEnabledTools(): array
    {
        $enabledTools = [];

        foreach ($this->gatherAvailableCommands() as $command) {
            if (!$command::isAvailable($this)) {
                continue;
            }

            $commandName = $command::getDefaultName();

            if (!\is_string($commandName)) {
                throw new \RuntimeException(\sprintf('Command "%s" has not configured a name', $command));
            }

            $enabledTools[$commandName] = $command;
        }

        return $enabledTools;
    }

    /**
     * @return array<string, class-string<DevToolsCommand>>
     */
    public function getEnabledTools(): array
    {
        if (null === $this->enabledTools) {
            $this->enabledTools = $this->gatherEnabledTools();
        }

        return $this->enabledTools;
    }

    /**
     * @return list<string>
     */
    public function getPhpVersions(): array
    {
        if (null === $this->phpVersions) {
            $this->phpVersions = $this->gatherPhpVersions();
        }

        return $this->phpVersions;
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }
}

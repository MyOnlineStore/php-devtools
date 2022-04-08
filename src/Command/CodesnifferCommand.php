<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

final class CodesnifferCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'codesniffer';

    /** @var string|null */
    protected static $defaultDescription = 'PHP_CodeSniffer';

    protected function getProcess(InputInterface $input): Process
    {
        if ($this->isGitHubFormat($input)) {
            return Process::fromShellCommandline(
                $this->withVendorBinPath('phpcs') . ' -q --report=checkstyle | cs2pr',
                timeout: null,
            );
        }

        return new Process(
            [$this->withVendorBinPath('phpcs')],
            timeout: null,
        );
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'phpcs.xml.dist')
            || \is_file($configuration->getRootDir() . 'phpcs.xml');
    }
}

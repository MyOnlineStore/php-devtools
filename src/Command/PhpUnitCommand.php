<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

final class PhpUnitCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'phpunit';

    /** @var string|null */
    protected static $defaultDescription = 'PHP Unit';

    protected function getProcess(InputInterface $input): Process
    {
        return new Process(
            [$this->withVendorBinPath('phpunit')],
            timeout: null,
        );
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'phpunit.xml.dist')
            || \is_file($configuration->getRootDir() . 'phpunit.xml');
    }
}

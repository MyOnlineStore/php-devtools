<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

final class PsalmCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'psalm';

    /** @var string|null */
    protected static $defaultDescription = 'Psalm';

    protected function getProcess(InputInterface $input): Process
    {
        $command = [$this->withVendorBinPath('psalm')];

        if ($this->isGitHubFormat($input)) {
            $command[] = '--output-format=github';
        }

        return new Process($command, timeout: null);
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'psalm.xml.dist')
            || \is_file($configuration->getRootDir() . 'psalm.xml');
    }
}

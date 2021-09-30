<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;

final class PhpUnitCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'phpunit';

    /** @var string|null */
    protected static $defaultDescription = 'PHP Unit';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withVendorBinPath('phpunit'),
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'phpunit.xml.dist')
            || \is_file($configuration->getRootDir() . 'phpunit.xml');
    }
}

<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;

final class PsalmCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'psalm';

    /** @var string|null */
    protected static $defaultDescription = 'Psalm';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withVendorBinPath('psalm'),
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'psalm.xml.dist')
            || \is_file($configuration->getRootDir() . 'psalm.xml');
    }
}

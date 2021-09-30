<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

use MyOnlineStore\DevTools\Configuration;

final class CodesnifferCommand extends DevToolsCommand
{
    /** @var string|null */
    protected static $defaultName = 'codesniffer';

    /** @var string|null */
    protected static $defaultDescription = 'PHP_CodeSniffer';

    /**
     * @inheritDoc
     */
    protected function getCommand(): array
    {
        return [
            $this->withVendorBinPath('phpcs'),
        ];
    }

    public static function isAvailable(Configuration $configuration): bool
    {
        return \is_file($configuration->getRootDir() . 'phpcs.xml.dist')
            || \is_file($configuration->getRootDir() . 'phpcs.xml');
    }
}

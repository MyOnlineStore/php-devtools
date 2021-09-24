<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

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
            $this->withBinPath('phpcs'),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getPossibleConfigurationFiles(): array
    {
        return [
            'phpcs.xml.dist',
            'phpcs.xml',
        ];
    }
}

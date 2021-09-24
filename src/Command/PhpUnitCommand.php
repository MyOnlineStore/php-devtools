<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

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
            $this->withBinPath('phpunit'),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getPossibleConfigurationFiles(): array
    {
        return [
            'phpunit.xml.dist',
            'phpunit.xml',
        ];
    }
}

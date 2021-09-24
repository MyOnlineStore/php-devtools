<?php
declare(strict_types=1);

namespace MyOnlineStore\DevTools\Command;

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
            $this->withBinPath('psalm'),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getPossibleConfigurationFiles(): array
    {
        return [
            'psalm.xml.dist',
            'psalm.xml',
        ];
    }
}

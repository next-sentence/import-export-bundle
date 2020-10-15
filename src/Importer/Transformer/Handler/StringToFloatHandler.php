<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer\Transformer\Handler;

use LWC\ImportExportBundle\Importer\Transformer\Handler;

final class StringToFloatHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    protected function process($type, $value)
    {
        return (float) $value;
    }

    protected function allows($type, $value): bool
    {
        return $type === 'float' || $type === 'percent';
    }
}

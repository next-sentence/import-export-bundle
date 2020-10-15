<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer\Transformer\Handler;

use LWC\ImportExportBundle\Importer\Transformer\Handler;

final class StringToBooleanHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    protected function process($type, $value)
    {
        return (bool) $value;
    }

    protected function allows($type, $value): bool
    {
        return $type === 'checkbox';
    }
}

<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter\Transformer\Handler;

use LWC\ImportExportBundle\Exporter\Transformer\Handler;

final class ArrayToStringHandler extends Handler
{
    /**
     * {@inheritdoc}
     */
    protected function process($key, $value)
    {
        return \implode('|', $value);
    }

    protected function allows($key, $value): bool
    {
        return \is_array($value);
    }
}

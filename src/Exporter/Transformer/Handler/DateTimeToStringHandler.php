<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter\Transformer\Handler;

use LWC\ImportExportBundle\Exporter\Transformer\Handler;

final class DateTimeToStringHandler extends Handler
{
    /** @var string */
    private $format;

    public function __construct(string $format = 'Y-m-d')
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    protected function process($key, $value)
    {
        return $value->format($this->format);
    }

    protected function allows($key, $value): bool
    {
        return $value instanceof \DateTimeInterface;
    }
}

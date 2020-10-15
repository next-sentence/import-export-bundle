<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer\Transformer\Handler;

use LWC\ImportExportBundle\Importer\Transformer\Handler;

final class StringToDateTimeHandler extends Handler
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
    protected function process($type, $value)
    {
        return \DateTime::createFromFormat($this->format, $value);
    }

    protected function allows($type, $value): bool
    {
        return $type === 'datetime';
    }
}

<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter\Transformer\Handler;

use LWC\ImportExportBundle\Exporter\Transformer\Handler;

final class IntegerToMoneyFormatHandler extends Handler
{
    /** @var array */
    private $keys;

    /** @var string */
    private $format;

    /**
     * @param string[] $allowedKeys
     */
    public function __construct(array $allowedKeys, string $format = '%.2n')
    {
        $this->keys = $allowedKeys;
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    protected function process($key, $value): ?string
    {
        return money_format($this->format, $value / 100);
    }

    protected function allows($key, $value): bool
    {
        return is_int($value) && in_array($key, $this->keys);
    }
}

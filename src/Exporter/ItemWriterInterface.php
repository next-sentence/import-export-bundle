<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ItemWriterInterface
{
    public function initQueue(string $queueName): void;

    /**
     * @param ResourceInterface[] $items
     */
    public function write(array $items): void;
}

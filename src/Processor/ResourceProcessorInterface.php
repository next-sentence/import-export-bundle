<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Processor;

interface ResourceProcessorInterface
{
    /**
     * @param mixed[] $data
     */
    public function process(array $data): void;
}

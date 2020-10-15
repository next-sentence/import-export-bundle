<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Processor;

interface MetadataValidatorInterface
{
    /**
     * @param string[] $headerKeys
     * @param mixed[] $dataset
     */
    public function validateHeaders(array $headerKeys, array $dataset): void;
}

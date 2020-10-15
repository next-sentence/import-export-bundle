<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer\Transformer;

interface TransformerPoolInterface
{
    /**
     * @return mixed (Something that can cast from string)
     */
    public function handle($type, $value);
}

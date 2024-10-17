<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

interface EventBasedImporterInterface extends ImporterInterface
{
    public function onPreImport(?\File $file = null): void;

    public function onPostImport(?\File $file = null): void;
}

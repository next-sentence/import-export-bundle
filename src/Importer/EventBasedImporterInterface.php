<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

interface EventBasedImporterInterface extends ImporterInterface
{
    public function onPreImport(?\SplFileInfo $file = null): void;

    public function onPostImport(?\SplFileInfo $file = null): void;
}

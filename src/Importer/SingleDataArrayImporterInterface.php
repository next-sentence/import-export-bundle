<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

interface SingleDataArrayImporterInterface extends ImporterInterface
{
    /**
     * @param mixed[] $dataToImport
     */
    public function importSingleDataArrayWithoutResult(array $dataToImport): void;
}

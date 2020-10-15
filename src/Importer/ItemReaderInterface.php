<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

interface ItemReaderInterface
{
    public function initQueue(string $queueName): void;

    public function readAndImport(SingleDataArrayImporterInterface $service, int $timeout): void;

    public function getMessagesImportedCount(): int;

    public function getMessagesSkippedCount(): int;
}

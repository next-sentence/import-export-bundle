<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Writer;

interface WriterInterface
{
    /**
     * @param mixed[] $data
     */
    public function write(array $data): void;

    public function setFile(string $filename): void;

    public function getFileContent(): string;

    public function finish(): void;
}

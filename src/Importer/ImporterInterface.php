<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

interface ImporterInterface
{
    public function import(string $fileName): ImporterResultInterface;
}

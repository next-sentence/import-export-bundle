<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Writer;

use Port\Spreadsheet\SpreadsheetWriter;

class PortSpreadsheetWriterFactory implements PortSpreadsheetWriterFactoryInterface
{
    public function get(string $filename): SpreadsheetWriter
    {
        return new SpreadsheetWriter(
            new \SplFileObject($filename, 'w')
        );
    }
}

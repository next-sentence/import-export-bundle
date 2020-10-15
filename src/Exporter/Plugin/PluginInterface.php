<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter\Plugin;

interface PluginInterface
{
    /**
     * @param mixed[] $resourceFields
     *
     * @return mixed[]
     */
    public function getData(string $id, array $resourceFields): array;

    /**
     * @param int[] $idsToExport
     */
    public function init(array $idsToExport): void;

    /**
     * @return string[]
     */
    public function getFieldNames(): array;
}

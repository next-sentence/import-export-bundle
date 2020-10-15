<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Exporter\Plugin;

interface PluginFactoryInterface
{
    public function create(string $pluginNamespace): PluginInterface;
}

<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle;

use LWC\ImportExportBundle\DependencyInjection\Compiler\MessageQueuePass;
use LWC\ImportExportBundle\DependencyInjection\Compiler\RegisterExporterPass;
use LWC\ImportExportBundle\DependencyInjection\Compiler\RegisterImporterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LWCImportExportBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterImporterPass());
        $container->addCompilerPass(new RegisterExporterPass());
        $container->addCompilerPass(new MessageQueuePass());
    }
}

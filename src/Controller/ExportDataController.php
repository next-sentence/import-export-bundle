<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Controller;

use LWC\ImportExportBundle\Exporter\ExporterRegistry;
use LWC\ImportExportBundle\Exporter\ResourceExporterInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportDataController
{
    /** @var array */
    private $resources;

    /** @var RepositoryInterface */
    private $repository;

    /** @var ServiceRegistryInterface */
    private $registry;

    /** @var RequestConfigurationFactoryInterface */
    private $requestConfigurationFactory;

    /** @var ResourcesCollectionProviderInterface */
    private $resourcesCollectionProvider;

    public function __construct(
        ServiceRegistryInterface $registry,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        RepositoryInterface $repository,
        array $resources
    ) {
        $this->registry = $registry;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->resourcesCollectionProvider = $resourcesCollectionProvider;
        $this->repository = $repository;
        $this->resources = $resources;
    }

    public function exportAction(
        Request $request,
        string $resource,
        string $format,
        ?string $outputFilename = null,
        ?string $exporter = null
    ): Response {
        $fileName = sprintf('%s-%s.%s', $outputFilename ? $outputFilename : $resource, date('Y-m-d'), $format);

        $exporter = $exporter ?? $resource;

        return $this->exportData($request, $exporter, $resource, $format, $fileName);
    }

    private function exportData(
        Request $request,
        string $exporter,
        string $resource,
        string $format,
        string $outputFilename
    ): Response {
        $metadata = Metadata::fromAliasAndConfiguration($exporter, $this->resources[$resource]);
        $configuration = $this->requestConfigurationFactory->create($metadata, $request);

        $name = ExporterRegistry::buildServiceName($exporter, $format);
        if (!$this->registry->has($name)) {
            throw new \Exception(sprintf("No exporter found of type '%s' for format '%s'", $exporter, $format));
        }
        /** @var ResourceExporterInterface $service */
        $service = $this->registry->get($name);

        $resources = $this->findResources($configuration, $this->repository);
        $service->export($this->getResourceIds($resources));

        $response = new Response($service->getExportedData());
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $outputFilename
        );

        $response->headers->set('Content-Type', 'application/' . $format);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @param ResourceGridView|array $resources
     *
     * @return int[]
     */
    private function getResourceIds($resources): array
    {
        if ($resources instanceof ResourceGridView
            && $resources->getData()->getAdapter() instanceof DoctrineORMAdapter) {
            $query = $resources->getData()->getAdapter()->getQuery()->setMaxResults(null);

            $ids = array_column($query->getArrayResult(), 'id');
        } else {
            $ids = array_map(function (ResourceInterface $resource) {
                return $resource->getId();
            }, $this->getResources($resources));
        }

        return array_unique($ids);
    }

    /**
     * @param ResourceGridView|array $resources
     */
    private function getResources($resources): array
    {
        return is_array($resources) ? $resources : $this->getResourcesItems($resources);
    }

    private function getResourcesItems(ResourceGridView $resources): array
    {
        $data = $resources->getData();

        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof Pagerfanta) {
            $results = [];

            for ($i = 0; $i < $data->getNbPages(); ++$i) {
                $data->setCurrentPage($i + 1);
                $results = array_merge($results, iterator_to_array($data->getCurrentPageResults()));
            }

            return $results;
        }
    }

    /**
     * @return ResourceGridView|array
     */
    private function findResources(RequestConfiguration $configuration, RepositoryInterface $repository)
    {
        return $this->resourcesCollectionProvider->get($configuration, $repository);
    }
}

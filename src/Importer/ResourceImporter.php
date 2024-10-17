<?php

declare(strict_types=1);

namespace LWC\ImportExportBundle\Importer;

use Doctrine\Persistence\ObjectManager;
use LWC\ImportExportBundle\Exception\ImporterException;
use LWC\ImportExportBundle\Exception\ItemIncompleteException;
use LWC\ImportExportBundle\Processor\ResourceProcessorInterface;
use Port\Reader\ReaderFactory;

class ResourceImporter implements EventBasedImporterInterface
{
    /** @var ReaderFactory */
    private $readerFactory;

    /** @var ObjectManager */
    protected $objectManager;

    /** @var ResourceProcessorInterface */
    protected $resourceProcessor;

    /** @var ImportResultLoggerInterface */
    protected $result;

    /** @var int */
    protected $batchSize;

    /** @var bool */
    protected $failOnIncomplete;

    /** @var bool */
    protected $stopOnFailure;

    /** @var int */
    protected $batchCount = 0;

    public function __construct(
        ReaderFactory $readerFactory,
        ObjectManager $objectManager,
        ResourceProcessorInterface $resourceProcessor,
        ImportResultLoggerInterface $importerResult,
        int $batchSize,
        bool $failOnIncomplete,
        bool $stopOnFailure
    ) {
        $this->readerFactory = $readerFactory;
        $this->objectManager = $objectManager;
        $this->resourceProcessor = $resourceProcessor;
        $this->result = $importerResult;
        $this->batchSize = $batchSize;
        $this->failOnIncomplete = $failOnIncomplete;
        $this->stopOnFailure = $stopOnFailure;
    }

    public function import(string $fileName): ImporterResultInterface
    {
        $reader = $this->readerFactory->getReader(new \SplFileObject($fileName));

        $this->result->start();

        foreach ($reader as $i => $row) {
            if ($this->importData((int) $i, $row)) {
                break;
            }
        }

        if ($this->batchCount) {
            $this->objectManager->flush();
        }

        $this->result->stop();

        return $this->result;
    }

    public function importData(int $i, array $row): bool
    {
        try {
            $this->resourceProcessor->process($row);
            $this->result->success($i);

            ++$this->batchCount;
            if ($this->batchSize && $this->batchCount === $this->batchSize) {
                $this->objectManager->flush();
                $this->batchCount = 0;
            }
        } catch (ItemIncompleteException $e) {
            $this->result->setMessage($e->getMessage());
            $this->result->getLogger()->critical($e->getMessage());
            if ($this->failOnIncomplete) {
                $this->result->failed($i);
                if ($this->stopOnFailure) {
                    return true;
                }
            } else {
                $this->result->skipped($i);
            }
        } catch (ImporterException $e) {
            $this->result->failed($i);
            $this->result->setMessage($e->getMessage());
            $this->result->getLogger()->critical($e->getMessage());
            if ($this->stopOnFailure) {
                return true;
            }
        }

        return false;
    }

    public function onPreImport(?\SplFileInfo $file = null): void
    {
        // TODO: Implement onPreImport() method.
    }

    public function onPostImport(?\SplFileInfo $file = null): void
    {
        // TODO: Implement onPostImport() method.
    }
}

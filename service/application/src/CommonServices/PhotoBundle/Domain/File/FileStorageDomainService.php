<?php

namespace CommonServices\PhotoBundle\Domain\File;

use CommonServices\PhotoBundle\Document\FileStorage;
use CommonServices\PhotoBundle\Repository\FileStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CommonServices\UserServiceBundle\Exception\InvalidFormException;

/**
 * Class FileStorageDomainService
 * @package CommonServices\UserServiceBundle\Domain\File
 */
class FileStorageDomainService
{
    public static $pendingAccountsChanges = [];

    /**
     * @var FileStorageRepository
     */
    private $fileStorageRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ItemService constructor.
     * @param ContainerInterface $container
     * @param FileStorageRepository $fileStorageRepository
     */
    public function __construct(ContainerInterface $container, FileStorageRepository $fileStorageRepository)
    {
        $this->container = $container;
        $this->fileStorageRepository = $fileStorageRepository;
    }

    /**
     * @param array $photoInfo
     * @throws InvalidFormException
     *
     * @return FileStorage
     */
    public function createFile(array $photoInfo) : FileStorage
    {
        $photoFactory = $this->container->get('photo_service.factory.file_storage_factory');

        return $photoFactory->createFileFromStorageInfo($photoInfo);

    }

    /**
     * @param array $fileInfo
     * @return FileStorage
     */
    public function analyzeFile(array $fileInfo) : FileStorage
    {
        $fileAnalyzer = $this->container->get('photo_service.factory.file_storage_factory');

        return $fileAnalyzer->processFileMetadata($fileInfo);

    }

    /**
     * @param array $fileInfo
     * @return array
     */
    public function generateKeywords(array $fileInfo) : array
    {
        $fileAnalyzer = $this->container->get('photo_service.factory.file_storage_factory');

        return $fileAnalyzer->getFileKeywords($fileInfo);

    }
}